<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Product;
use App\Models\Stock;
use App\Models\PrimaryCategory;
use App\Mail\TestMail;

class ItemController extends Controller {
    public function __construct() {
        $this->middleware('auth:users');

        $this->middleware(function ($request, $next) {
            //productidの取得
            $id = $request->route()->parameter('item');
            if (!is_null($id)) {
                $itemId = Product::availableItems()->where('products.id', $id)->exists();
                //商品が未販売なら404ページを表示する
                abort_unless($itemId, 404);
            }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        Mail::to('test@example.com')->send(new TestMail());

        $attributes = $request->only(['category', 'keyword', 'pagination', 'sort']);

        //categoryが設定されていない場合は0
        $categoryId = $attributes['category'] ?? '0';

        $keyword = $attributes['keyword'] ?? null;

        //paginationが設定されていない場合は20
        $pagination = $attributes['pagination'] ?? '20';

        $sortType = $attributes['sort'] ?? '0';

        $sortOrder = \SortOrderConstant::SORT_ORDER;

        $products = [];

        //指定無しまたはおすすめ順
        if (is_null($sortType) || $sortType === $sortOrder['recommend']['value']) {
            $products = Product::availableItems()->selectCategory($categoryId)->searchKeyword($keyword)->orderBySortOrderASC()->paginate($pagination);
        }

        //価格の高い順
        if ($sortType === $sortOrder['higherPrice']['value']) {
            $products = Product::availableItems()->selectCategory($categoryId)->searchKeyword($keyword)->orderByPriceDESC()->paginate($pagination);
        }

        //価格の低い順
        if ($sortType === $sortOrder['lowerPrice']['value']) {
            $products = Product::availableItems()->selectCategory($categoryId)->searchKeyword($keyword)->orderByPriceASC()->paginate($pagination);
        }

        //新しい順
        if ($sortType === $sortOrder['newst']['value']) {
            $products = Product::availableItems()->selectCategory($categoryId)->searchKeyword($keyword)->orderByCreatedDESC()->paginate($pagination);
        }

        //古い順
        if ($sortType === $sortOrder['oldest']['value']) {
            $products = Product::availableItems()->selectCategory($categoryId)->searchKeyword($keyword)->orderCreatedASC()->paginate($pagination);
        }

        $categories = PrimaryCategory::with('secondary')->get();

        return view('users.index', compact('products', 'categories'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $product = Product::findOrfail($id);
        $quantity = Stock::productId($product->id)->sum('quantity');
        if ($quantity > 9) {
            $quantity = 9;
        }

        return view('users.show', compact('product', 'quantity'));
    }
}
