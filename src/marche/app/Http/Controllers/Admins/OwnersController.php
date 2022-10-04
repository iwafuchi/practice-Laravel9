<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OwnerRegisterRequest;
use App\Models\Owner;
use App\Models\Shop;
use Exception;

class OwnersController extends Controller {
    public function __construct() {
        $this->middleware('auth:admins');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $owners = Owner::select('id', 'name', 'email', 'created_at')->paginate(3);
        return view('admins.owners.index', compact('owners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admins.owners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OwnerRegisterRequest $request) {

        try {
            DB::transaction(function () use ($request) {
                $owner = Owner::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                Shop::create([
                    'owner_id' => $owner->id,
                    'name' => '店名を入力して下さい',
                    'information' => '店の情報を入力して下さい',
                    'filename' => 'ファイル名を入力して下さい',
                    'is_selling' => true
                ]);
            }, 2);
        } catch (Exception $e) {
            Log::error($e);
            throw $e;
        }

        return redirect()
            ->route('admins.owners.index')
            ->with([
                'message' => 'オーナー登録を実施しました',
                'status' => 'info'
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $owner = Owner::findOrFail($id);
        return view('admins.owners.edit', compact('owner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $owner = Owner::findOrFail($id);
        $owner->name = $request->name;
        $owner->email = $request->email;
        $owner->password = Hash::make($request->password);
        $owner->save();

        return redirect()->route('admins.owners.index')
            ->with([
                'message' => 'オーナー情報を更新しました。',
                'status' => 'info'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //SoftDelete
        Owner::findOrFail($id)->delete();
        return redirect()->route('admins.owners.index')
            ->with([
                'message' => 'オーナー情報を削除しました。',
                'status' => 'alert'
            ]);
    }

    public function expiredOwnerIndex() {
        $expiredOwners = Owner::onlyTrashed()->get();
        return view('admins.expired-owners', compact('expiredOwners'));
    }
    public function expiredOwnerDestroy($id) {
        Owner::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admins.expired-owners.index');
    }
}
