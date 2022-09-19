<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Owners\ShopController;
use App\Http\Controllers\Owners\ImageController;
use App\Http\Controllers\Owners\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('shops')
    ->middleware('auth:owners')
    ->group(function () {
        Route::get('index', [ShopController::class, 'index'])
            ->name('shops.index');
        Route::get('edit/{shop}', [ShopController::class, 'edit'])
            ->name('shops.edit');
        Route::post('update/{shop}', [ShopController::class, 'update'])
            ->name('shops.update');
    });

Route::resource('images', ImageController::class)
    ->middleware(['auth:owners'])
    ->except(['show']);

Route::resource('products', ProductController::class)
    ->middleware(['auth:owners'])
    ->except(['show']);

Route::get('/dashboard', function () {
    return view('owners.dashboard');
})->middleware(['auth:owners'])->name('dashboard');

require __DIR__ . '/authOwner.php';
