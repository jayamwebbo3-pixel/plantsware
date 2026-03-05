<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateOrderStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically updates order statuses, closing return windows after 3 days.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting Order Status Updates...");

        // Find delivered orders older than 3 days and mark them as completed
        $expiredDeliveredOrders = \App\Models\Order::where('status', 'delivered')
            ->whereNotNull('delivered_at')
            ->where('delivered_at', '<', \Carbon\Carbon::now()->subDays(3))
            ->get();
            
        foreach ($expiredDeliveredOrders as $order) {
            $order->update(['status' => 'completed']);
            $this->info("Order #{$order->order_number} return window expired. Marked as completed.");
        }
        
        $this->info("Finished updating order statuses. Updated " . count($expiredDeliveredOrders) . " records.");
    }
}
