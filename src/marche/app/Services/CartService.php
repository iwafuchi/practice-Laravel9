<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Cart;
use App\Models\Owner;
use Illuminate\Database\Query\JoinClause;

class CartService {
    public static function getItemsInCart($items) {
        $products = [];
        foreach ($items as $item) {
            $p = Product::findOrFail($item->product_id);
            \DB::enableQueryLog();
            //オーナー情報を取得
            $owner = $p->shop->owner;
            $owner2 = $p->shop->join('owners', function (JoinClause $join) {
                $join->on('shops.owner_id', '=', 'owners.id');
            })->select('owners.name', 'owners.email')
                ->where('owners.id', $p->shop->owner_id)->get();
            dd($owner, $owner2, \DB::getQueryLog());

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
