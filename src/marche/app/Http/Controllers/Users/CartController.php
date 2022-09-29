<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\User;
use App\Models\Stock;

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

    public function add(Request $request) {
        $itemInCart = Cart::userId(Auth::id())
            ->productId($request->product_id)
            ->first();

        if ($itemInCart) {
            $itemInCart->quantity += $request->quantity;
            $itemInCart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }
        return redirect()->route('users.cart.index');
    }

    public function delete($id) {
        Cart::productId($id)
            ->userId(Auth::id())
            ->delete();

        return redirect()->route('users.cart.index');
    }

    public function checkout() {
        $user = User::findOrFail(Auth::id());
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

        // dd('test');

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [$lineItems],
            'mode' => 'payment',
            'success_url' => route('users.cart.success'),
            'cancel_url' => route('users.cart.index'),
        ]);

        return redirect($session->url, 303);
    }

    public function success() {
        Cart::userId(Auth::id())->delete();

        return redirect()->route('users.cart.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
}
