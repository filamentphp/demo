<?php

use App\Http\Livewire\Form;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;

\Illuminate\Support\Facades\Route::get('form', Form::class);


Route::get('/', [FrontendController::class, 'index']);
Route::prefix('products')->group( function() {
    Route::get('/{slug}', [FrontendController::class, 'viewProduct'])->name('view.product');
});
