<?php

use App\Livewire\Form;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


Route::get('form', Form::class);

Route::view('/', 'pages.home');


Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::get('/contact', function () {
    return view('pages.contact-us');
});


