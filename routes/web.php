<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// メール認証画面
Route::get('/verify-email', function () {
    return view('auth.verify-email');
});


require __DIR__.'/auth.php';

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth:admin'], function () {
    Route::get('home', [Admin\HomeController::class, 'index'])->name('home');
    Route::get('users', [UserController::class, 'index'])->name('users.index'); //会員一覧ページ
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show'); //会員詳細ページ

    Route::get('restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');//店舗一覧ページ
    Route::get('restaurants/{restaurant}', [RestaurantController::class, 'show'])->name('restaurants.show');//店舗詳細ページ
    Route::get('restaurants/create', [RestaurantController::class, 'create'])->name('restaurants.create');//店舗追加ページ
    Route::post('restaurants/store', [RestaurantController::class, 'store'])->name('restaurants.store');//店舗追加機能
    Route::get('restaurants/{restaurant}/edit', [RestaurantController::class, 'edit'])->name('restaurants.edit');//店舗編集ページ
    Route::patch('restaurants/{restaurant}/updata', [RestaurantController::class, 'update'])->name('restaurants.update');//店舗編集機能
    Route::delete('restaurants/{restaurant}', [RestaurantController::class, 'destroy'])->name('restaurants.destroy');//店舗削除機能
});