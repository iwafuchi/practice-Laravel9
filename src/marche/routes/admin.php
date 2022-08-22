<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admins\OwnersController;

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

Route::get('/', function () {
    return view('admins.welcome');
});

Route::resource('owners', OwnersController::class)->middleware(['auth:admins']);

Route::get('/dashboard', function () {
    return view('admins.dashboard');
})->middleware(['auth:admins'])->name('dashboard');

require __DIR__ . '/authAdmin.php';
