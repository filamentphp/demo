<?php

use App\Http\Livewire\Form;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;

\Illuminate\Support\Facades\Route::get('form', Form::class);


Route::get('/', [FrontendController::class, 'index']);
Route::prefix('products')->group( function() {
    Route::get('/{slug}', [FrontendController::class, 'viewProduct'])->name('view.product');
});
Route::get('cart', [FrontendController::class, 'cart'])->name('cart');
Route::get('checkout', [FrontendController::class, 'checkout'])->name('checkout');
Route::get('orders/confirmation', [FrontendController::class, 'orderConfirmation'])->name('orders.confirmation');
