<?php

namespace App\Http\Controllers\Owners;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;


class ShopController extends Controller {
    public function __construct() {
        $this->middleware('auth:owners');
    }

    public function index() {
        $ownerId = Auth::id();
        $shops = Shop::where('owner_id', $ownerId)->get();
        return view('owners.shops.index', compact('shops'));
    }

    public function edit($id) {
    }

    public function update(Request $request, $id) {
    }
}
