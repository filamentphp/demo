<?php

use App\Livewire\Form;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Mail\MyTestEmail;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Mail;

Route::get('form', Form::class);

//Route::view('/', 'pages.home')->name('index');
Route::get('/', [ShopController::class, 'index'])->name('index');


Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

//Route::get('/contact', function () {
//    return view('pages.contact-us');
//})->name('contact');

Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');





Route::get('/shop', function () {
    return view('pages.shop_dikson');
})->name('shop.index');

Route::get('/shop/muster', [ShopController::class, 'showMusterProducts'])->name('shop.muster');
Route::get('/shop/dikson', [ShopController::class, 'showDiksonProducts'])->name('shop.dikson');


// Cart routes
Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
//Route::post('/cart/updatess', [CartController::class, 'updateCartItem'])->name('cart.updatesss');
Route::delete('/cart/remove/{id}', [CartController::class, 'removeCartItem'])->name('cart.remove');
Route::post('/cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');


Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::get('/thank-you', [ShopController::class, 'tahnkYou'])->name('thankyou');

Route::get('/about-us', function () {
    return view('pages.about_us');
})->name('about_us');

Route::post('/place-order', [ShopController::class, 'store'])->name('order.store');

//Pages
Route::get('/hairdresser', function () {
    return view('pages.hairdresser');
})->name('hairdresser');

Route::get('/beauty', function () {
    return view('pages.beauty');
})->name('beauty');

Route::get('/hygiene-safety', function () {
    return view('pages.hygiene-safety');
})->name('hygiene-safety');

Route::get('/video', function () {
    return view('pages.video');
})->name('video');

Route::get('/downloads', function () {
    return view('pages.downloads');
})->name('downloads');





Route::get('/testroute', function() {
    $name = "Funny Coder";

    Mail::to('soufianjill@gmail.com')->send(new MyTestEmail($name));
});

