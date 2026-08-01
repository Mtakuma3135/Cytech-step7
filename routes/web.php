<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| ここで Web ルートを定義します。RouteServiceProvider によって
| "web" ミドルウェアグループが自動的に適用されます。
|
*/

// ------------------------------
// ショップ（一般顧客向け・誰でも閲覧可）
// ------------------------------
Route::get('/', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product}', [ShopController::class, 'show'])->name('shop.show');

// ------------------------------
// カート（ゲストでも利用可）
// ------------------------------
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');

Route::middleware('auth')->group(function () {

    // ------------------------------
    // ユーザー管理
    // ------------------------------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ------------------------------
    // 購入手続き・注文
    // ------------------------------
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/{order}/complete', [CheckoutController::class, 'complete'])->name('checkout.complete');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // ------------------------------
    // 商品管理（管理者用）
    // ------------------------------
    // 商品一覧
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    // 商品作成
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    // 商品保存
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    // 商品検索
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    // 商品詳細
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
    // 商品編集
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    // 商品更新
    Route::patch('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::put('products/{product}', [ProductController::class, 'update']); // PUT用
    // 商品削除
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    // 商品画像削除
    Route::delete('products/{product}/image', [ProductController::class, 'destroyImage'])->name('products.destroyImage');

    // ------------------------------
    // メーカー管理（管理者用）
    // ------------------------------
    Route::get('/admin/companies', [ProductController::class, 'companyIndex'])->name('admin.companies.index');
    Route::post('/admin/companies', [ProductController::class, 'storeCompany'])->name('admin.companies.store');
    Route::delete('/admin/companies/{company}', [ProductController::class, 'destroyCompany'])->name('admin.companies.destroy');
});

// 旧・単純購入API（在庫チェック＋salesレコード作成のみ。互換性のため残置）
Route::post('/purchase', [SalesController::class, 'purchase']);

require __DIR__ . '/auth.php';
