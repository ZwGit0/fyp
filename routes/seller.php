<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SellerAuthController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SellerProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SellerTermsController;
use App\Http\Controllers\SellerAboutController;
use App\Http\Livewire\SellerChat;

// Sellers
Route::prefix('seller')->group(function () {
    Route::get('/', function () {
        return view('seller.welcome');
    })->name('seller.welcome');

    Route::post('/sellerLogout', function () {
        Auth::guard('seller')->logout();
        return redirect()->route('seller.welcome');
    })->name('sellerLogout');    
    
    Route::get('/sellerRegister', [SellerAuthController::class, 'showRegisterForm'])->name('seller.sellerRegister');
    Route::post('/sellerRegister', [SellerAuthController::class, 'register']);
    Route::get('/sellerLogin', [SellerAuthController::class, 'showLoginForm'])->name('seller.sellerLogin');
    Route::post('/sellerLogin', [SellerAuthController::class, 'login']);
    Route::post('/logout', [SellerAuthController::class, 'logout'])->name('seller.logout');

    Route::get('/seller/password/reset', [SellerAuthController::class, 'showLinkRequestForm'])->name('seller.password.request');
    Route::post('/seller/password/email', [SellerAuthController::class, 'sendResetLinkEmail'])->name('seller.password.email');
    Route::get('/seller/password/reset/{token}', [SellerAuthController::class, 'showResetForm'])->name('seller.password.reset');
    Route::post('/seller/password/reset', [SellerAuthController::class, 'reset'])->name('seller.password.update');
    
    Route::get('/terms', [SellerTermsController::class, 'index'])->name('seller.terms');
    Route::get('/about', [SellerAboutController::class, 'index'])->name('seller.about');

    Route::middleware('auth:seller')->group(function () {
        Route::get('/dashboard', function () {
            return view('seller.dashboard');
        })->name('seller.dashboard');

        // Seller Product Management Routes
        Route::get('/products/list', [SellerProductController::class, 'index'])->name('seller.products.list'); 
        Route::get('/products/create', [SellerProductController::class, 'create'])->name('seller.products.create');
        Route::post('/products', [SellerProductController::class, 'store'])->name('seller.products.store');
        Route::get('/products/{product}/edit', [SellerProductController::class, 'edit'])->name('seller.products.edit');
        Route::put('/products/{product}', [SellerProductController::class, 'update'])->name('seller.products.update');
        Route::delete('/products/{product}', [SellerProductController::class, 'destroy'])->name('seller.products.destroy');
    
        // Add the route for fetching categories based on product type
        Route::get('/products/getCategoriesByProductType', [SellerProductController::class, 'getCategoriesByProductType'])
            ->name('seller.products.getCategoriesByProductType');

        Route::get('/dashboard', [SellerController::class, 'index'])->name('seller.dashboard');
        Route::get('/profile/sellerProfile', [SellerController::class, 'profile'])->name('seller.profile.sellerProfile');
        Route::get('/profile/sellerProfileEdit', [SellerController::class, 'editProfile'])->name('seller.profile.edit');
        Route::put('/profile/sellerProfile', [SellerController::class, 'updateProfile'])->name('seller.profile.update');
        Route::delete('profile/sellerProfileDelete', [SellerController::class, 'destroy'])->name('seller.profile.delete');

        Route::get('/orders/to-ship', [SellerProductController::class, 'showOrdersToShip'])->name('seller.orders.to-ship');
        Route::post('/order/{id}/ship', [SellerProductController::class, 'markAsShipped'])->name('order.ship');

        Route::get('/chat', SellerChat::class)->name('seller.chat');
    });
});