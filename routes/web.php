<?php

use App\Livewire\ClientProjects;
use App\Livewire\Form;
use Illuminate\Support\Facades\Route;

Route::get('form', Form::class);

Route::get('client/projects', ClientProjects::class)->middleware('auth')->name('client.projects');

Route::redirect('login-redirect', 'login')->name('login');
