<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;
use App\Models\PaymentTransaction;

Schedule::call(function () {
    // 1. Cleanup expired temp carts and restore stock (10 mins)
    app(\App\Services\TempCartService::class)->cleanupExpiredTempCarts();

    // 2. Expire old pending payment transactions
    $expiredTransactions = PaymentTransaction::whereIn('status', ['INITIATED', 'PENDING'])
        ->where('created_at', '<', now()->subMinutes(10))
        ->get();

    foreach ($expiredTransactions as $transaction) {
        $transaction->update(['status' => 'EXPIRED']);
        // (Note: No order deletion needed here as orders are now created only after SUCCESS)
    }
})->everyMinute();

Schedule::command('orders:update-statuses')->daily();
