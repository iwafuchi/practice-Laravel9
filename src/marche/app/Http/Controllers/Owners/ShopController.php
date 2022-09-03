<?php

namespace App\Http\Controllers\Owners;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Shop;


class ShopController extends Controller {
    public function __construct() {
        $this->middleware('auth:owners');

        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter('shop');
            if (!is_null($id)) {
                $shopsOwnerId = Shop::findOrFail($id)->owner->id;
                $shopId = (int)$shopsOwnerId;
                $ownerId = Auth::id();
                if ($shopId !== $ownerId) {
                    abort(404);
                }
            }
            return $next($request);
        });
    }

    public function index() {
        $shops = Shop::where('owner_id', Auth::id())->get();
        return view('owners.shops.index', compact('shops'));
    }

    public function edit($id) {
        $shop = Shop::findOrFail($id);
        return view('owners.shops.edit', compact('shop'));
    }

    //upload image
    public function update(Request $request, $id) {
        $imageFile = $request->image;
        if (!is_null($imageFile) && $imageFile->isValid()) {
            Storage::putFile('public/shops', $imageFile);
            dd($imageFile);
        }
        return redirect()->route('owners.shops.index');
    }
}
