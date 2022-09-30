<?php

namespace App\Http\Controllers\Owners;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Owner;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Stock;
use App\Models\PrimaryCategory;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class ProductController extends Controller {
    public function __construct() {
        $this->middleware('auth:owners');

        $this->middleware(function ($request, $next) {
            //productidの取得
            $id = $request->route()->parameter('product');
            if (!is_null($id)) {
                $productOwnerId = Product::findOrFail($id)->shop->owner->id;
                $productId = (int)$productOwnerId;
                //productidと認証済みユーザーのIDが同じでなかったら404画面を表示する
                if ($productId !== Auth::id()) {
                    abort(404);
                }
            }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $owners = Owner::with(['shop' => [
            'product' => [
                'imageFirst'
            ]
        ]])->where('id', Auth::id())->get();
        return view('owners.products.index', compact('owners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $shops = Shop::ownerId(Auth::id())
            ->select('id', 'name')
            ->get();

        $images = Image::ownerId(Auth::id())
            ->select('id', 'title', 'filename')
            ->orderBy('updated_at', 'desc')
            ->get();

        $categories = PrimaryCategory::with('secondary')->get();

        return view('owners.products.create', compact('shops', 'images', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request) {

        try {
            DB::transaction(function () use ($request) {
                $product = Product::create([
                    'name' => $request->name,
                    'information' => $request->information,
                    'price' => $request->price,
                    'sort_order' => $request->sort_order,
                    'shop_id' => $request->shop_id,
                    'secondary_category_id' => $request->category,
                    'image1' => $request->image1,
                    'image2' => $request->image2,
                    'image3' => $request->image3,
                    'image4' => $request->image4,
                    'is_selling' => $request->is_selling
                ]);

                Stock::create([
                    'product_id' => $product->id,
                    'type' => 1,
                    'quantity' => $request->quantity,
                ]);
            }, 2);
        } catch (Exception $e) {
            Log::error($e);
            throw $e;
        }

        return redirect()
            ->route('owners.products.index')
            ->with([
                'message' => '商品登録を実施しました',
                'status' => 'info'
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $product = Product::findOrFail($id);

        $quantity = Stock::productId($product->id)->sum('quantity');

        $shops = Shop::ownerId(Auth::id())
            ->select('id', 'name')
            ->get();

        $images = Image::ownerId(Auth::id())
            ->select('id', 'title', 'filename')
            ->orderBy('updated_at', 'desc')
            ->get();

        $categories = PrimaryCategory::with('secondary')->get();

        return view(
            'owners.products.edit',
            compact('product', 'quantity', 'shops', 'images', 'categories')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id) {
        $request->validate([
            'current_quantity' => ['required', 'integer']
        ]);

        $product = Product::findOrFail($id);

        $quantity = Stock::productId($product->id)->sum('quantity');

        if ($request->current_quantity !== $quantity) {
            return redirect()->route('owners.products.edit', ['product' => $id])
                ->with([
                    'message' => '在庫数が変更されています。再度確認して下さい',
                    'status' => 'alert'
                ]);
        }

        try {
            DB::transaction(function () use ($request, $product) {
                $product->update($request->all());
                $product->save();

                if ($request->type === \ProductConstant::PRODUCT_LIST['add']) {
                    $newQuantity = $request->quantity;
                }

                if ($request->type === \ProductConstant::PRODUCT_LIST['reduce']) {
                    $newQuantity = $request->quantity * -1;
                }

                Stock::create([
                    'product_id' => $product->id,
                    'type' => $request->type,
                    'quantity' => $newQuantity,
                ]);
            }, 2);
        } catch (Exception $e) {
            Log::error($e);
            throw $e;
        }

        return redirect()
            ->route('owners.products.index')
            ->with([
                'message' => '商品情報を更新しました',
                'status' => 'info'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        Product::findOrFail($id)->delete();

        return redirect()->route('owners.products.index')
            ->with([
                'message' => '商品を削除しました。',
                'status' => 'alert'
            ]);
    }
}
