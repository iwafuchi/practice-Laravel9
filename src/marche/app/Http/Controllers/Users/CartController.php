<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\User;
use App\Models\Stock;
use App\Services\CartService;

class CartController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $user = User::findOrFail(Auth::id());
        $products = $user->products;
        $totalPrice = 0;

        foreach ($products as $product) {
            $totalPrice += $product->price * $product->pivot->quantity;
        }

        return view('users.cart', compact('products', 'totalPrice'));
    }

    /**
     * add function
     * 商品をカートに追加時の処理
     * @param Request $request
     * @return void
     */
    public function add(Request $request) {
        $attributes = $request->only(['product_id', 'quantity']);
        $itemInCart = Cart::userId(Auth::id())
            ->productId($attributes['product_id'])
            ->first();

        if ($itemInCart) {
            $itemInCart->quantity += $attributes['quantity'];
            $itemInCart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $attributes['product_id'],
                'quantity' => $attributes['quantity']
            ]);
        }
        return redirect()->route('users.cart.index');
    }

    /**
     * delete function
     * カートから商品を削除する
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id) {
        Cart::productId($id)
            ->userId(Auth::id())
            ->delete();

        return redirect()->route('users.cart.index');
    }

    /**
     * checkout function
     * 購入時の処理
     * @return void
     */
    public function checkout() {
        $user = User::findOrFail(Auth::id());

        /////
        $items = Cart::userId(Auth::id())->get();
        $products = CartService::getItemsInCart($items);
        dd($products);
        /////

        $products = $user->products;

        $lineItems = [];

        foreach ($products as $product) {
            $quantity = Stock::productId($product->id)->sum('quantity');
            if ($product->pivot->quantity > $quantity) {
                return redirect()->route('users.cart.index');
            }

            $lineItem = [
                'price_data' => [
                    'unit_amount' => $product->price,
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $product->name,
                        'description' => $product->information,
                    ],
                ],
                'quantity' => $product->pivot->quantity,
            ];
            array_push($lineItems, $lineItem);
        }
        foreach ($products as $product) {
            Stock::create([
                'product_id' => $product->id,
                'type' => \ProductConstant::PRODUCT_LIST['reduce'],
                'quantity' => $product->pivot->quantity * -1,
            ]);
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [$lineItems],
            'mode' => 'payment',
            'success_url' => route('users.cart.success'),
            'cancel_url' => route('users.cart.cancel'),
        ]);

        return redirect($session->url, 303);
    }

    public function success() {
        Cart::userId(Auth::id())->delete();

        return redirect()->route('users.items.index');
    }

    public function cancel() {
        $user = User::findOrFail(Auth::id());

        foreach ($user->products as $product) {
            Stock::create([
                'product_id' => $product->id,
                'type' => \ProductConstant::PRODUCT_LIST['cancel'],
                'quantity' => $product->pivot->quantity,
            ]);
        }

        return redirect()->route('users.cart.index');
    }
}
