<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Cart;
use App\Models\Owner;

class CartService {
    public static function getItemsInCart($items) {
        $products = [];
        foreach ($items as $item) {
            $p = Product::findOrFail($item->product_id);

            //オーナー情報を取得
            $owner = $p->shop->owner;

            //オーナー情報の連想配列の値を取得
            // $values = array_values($owner);

            // $keys = ['ownerName', 'email'];

            //オーナー情報のキーを変更
            $ownerInfo = ['ownerName' => $owner->name, 'email' => $owner->email];

            //商品情報配列
            $product = Product::where('id', $item->product_id)->select('id', 'name', 'price')->get()->toArray();

            //在庫数配列
            $quantity = Cart::productId($item->product_id)->select('quantity')->get()->toArray();

            //配列の結合
            $result = array_merge($product[0], $ownerInfo, $quantity[0]);

            //配列に追加
            array_push($products, $result);
        }
        return $products;
    }
}
