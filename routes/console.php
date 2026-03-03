<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;
use App\Models\PaymentTransaction;

Schedule::call(function () {
    $expiredTransactions = PaymentTransaction::whereIn('status', ['INITIATED', 'PENDING'])
        ->where('created_at', '<', now()->subMinutes(10))
        ->get();

    foreach ($expiredTransactions as $transaction) {
        $transaction->update(['status' => 'EXPIRED']);
        
        // As requested: if payment fails (expires), do not create/keep the order.
        if ($transaction->order_id) {
            $orderToFail = \App\Models\Order::find($transaction->order_id);
            if ($orderToFail) {
                $orderToFail->items()->delete();
                $orderToFail->delete();
            }
        }
    }
})->everyMinute();

Schedule::command('orders:update-statuses')->daily();
