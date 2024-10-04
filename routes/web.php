<?php

use App\Livewire\Form;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Mail\MyTestEmail;
use Illuminate\Support\Facades\Mail;

Route::get('form', Form::class);

Route::view('/', 'pages.home')->name('index');


Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::get('/contact', function () {
    return view('pages.contact-us');
});

Route::get('/shop', function () {
    return view('pages.shop_dikson');
})->name('shop.index');

Route::get('/shop/muster', [ShopController::class, 'showMusterProducts'])->name('shop.muster');
Route::get('/shop/dikson', [ShopController::class, 'showMusterProducts'])->name('shop.dikson');


Route::get('/testroute', function() {
    $name = "Funny Coder";

    Mail::to('soufianjill@gmail.com')->send(new MyTestEmail($name));
});
