<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Cart;

class CartService {
    public static function getItemsInCart($items) {
        $products = [];
        foreach ($items as $item) {

            //該当プロダクトを取得
            $product = Product::findOrFail($item->product_id);

            //オーナー情報を取得
            $ownerInfo = $product
                ->shop->hasByJoin('owner')
                ->where('owners.id', $product->shop->owner_id)
                ->select('owners.name as ownerName', 'owners.email')
                ->get()->toArray();

            //商品情報配列
            $productInfo = Product::where('id', $item->product_id)->select('id', 'name', 'price')->get()->toArray();

            //在庫数配列
            $quantity = Cart::productId($item->product_id)->select('quantity')->get()->toArray();

            //配列の結合
            $result = array_merge($productInfo[0], $ownerInfo[0], $quantity[0]);

            //配列に追加
            array_push($products, $result);
        }
        return $products;
    }
}
