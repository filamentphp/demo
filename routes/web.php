<?php

use App\Livewire\Form;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;

Route::get('form', Form::class);

Route::view('/', 'pages.home');


Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::get('/contact', function () {
    return view('pages.contact-us');
});

Route::get('/shop', function () {
    return view('pages.shop_dikson');
});

Route::get('/shop/muster', [ShopController::class, 'showMusterProducts'])->name('shop.muster');


