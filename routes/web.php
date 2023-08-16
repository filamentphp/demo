<?php

use App\Http\Livewire\Form;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DownloadPdfController;

Route::get('form', Form::class);


Route::get('/export/pdf/categories/all', [DownloadPdfController::class, 'downloadAll'])->name('export.categories.all');
