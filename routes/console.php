<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    Artisan::call('down', ['--render' => 'maintenance']);
    Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
    Artisan::call('up');
})->hourly();

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
