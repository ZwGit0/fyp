<?php

use App\Filament\Resources\ProductResource;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\Auth\SellerAuthController;
use App\Http\Livewire\PublicChat;

// Users
Route::post('/logout', function () {
    Auth::guard('web')->logout();
    return redirect()->route('home');
})->name('logout');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::post('/login', [LoginController::class, 'login']);

//Routes for password reset
Route::get('/password/reset', [LoginController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [LoginController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [LoginController::class, 'reset'])->name('password.update');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

Route::post('/register', [RegisterController::class, 'register']);

Route::get('/category/{groupName}', [ProductController::class, 'getProductsByCategory'])->name('category.products');

Route::get('/product-type/{id}', [ProductController::class, 'getProductsByType'])->name('product-type.products');

Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.details');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/search', [ProductController::class, 'searchProducts'])->name('search.products');

Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::middleware(['auth:web'])->group(function () {
    Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
    Route::post('/cart/update/{cartId}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/remove/{cartId}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.view');
  
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile/delete', [ProfileController::class, 'destroy'])->name('profile.delete');

    Route::get('/payment', [CartController::class, 'checkout'])->name('order.payment');

    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
    Route::get('/order-history', [OrderController::class, 'orderHistory'])->name('order.orderHistory');
    Route::get('/order/status', [OrderController::class, 'showAllStatuses'])->name('order.orderStatus');
    Route::post('/order/{id}/receive', [OrderController::class, 'markAsReceived'])->name('order.receive');
    Route::post('/order/{id}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
    Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.show');

    Route::get('/chat', PublicChat::class)->name('chat');
});

Route::prefix('seller')->group(function () {
    Route::get('/', function () {
        return view('seller.welcome');
    })->name('seller.welcome');

    Route::get('/seller/logout-and-redirect', [SellerAuthController::class, 'logout'])->name('seller.logoutAndRedirect');

});

