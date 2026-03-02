<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;
use App\Models\PaymentTransaction;

Schedule::call(function () {
    PaymentTransaction::whereIn('status', ['INITIATED', 'PENDING'])
        ->where('created_at', '<', now()->subMinutes(10))
        ->update(['status' => 'EXPIRED']);
})->everyMinute();
