<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;

class ItemController extends Controller {
    public function __construct() {
        $this->middleware('auth:users');

        $this->middleware(function ($request, $next) {
            //productidの取得
            $id = $request->route()->parameter('item');
            if (!is_null($id)) {
                $itemId = Product::availableItems()->where('products.id', $id)->exists();
                //productidと認証済みユーザーのIDが同じでなかったら404画面を表示する
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
    public function index() {
        $products = Product::availableItems()->get();

        return view('users.index', compact('products'));
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
