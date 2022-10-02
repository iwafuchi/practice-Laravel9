<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;

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
        $sortType = $request->sort;
        $sortOrder = \SortOrderConstant::SORT_ORDER;
        $products = [];
        //指定無しまたはおすすめ順
        if (is_null($sortType) || $sortType === $sortOrder['recommend']['value']) {
            $products = Product::availableItems()->orderBySortOrderASC()->paginate($request->pagination);
        }
        //価格の高い順
        if ($sortType === $sortOrder['higherPrice']['value']) {
            $products = Product::availableItems()->orderByPriceDESC()->paginate($request->pagination);
        }
        //価格の低い順
        if ($sortType === $sortOrder['lowerPrice']['value']) {
            $products = Product::availableItems()->orderByPriceASC()->paginate($request->pagination);
        }
        //新しい順
        if ($sortType === $sortOrder['newst']['value']) {
            $products = Product::availableItems()->orderByCreatedDESC()->paginate($request->pagination);
        }
        //古い順
        if ($sortType === $sortOrder['oldest']['value']) {
            $products = Product::availableItems()->orderCreatedASC()->paginate($request->pagination);
        }
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
