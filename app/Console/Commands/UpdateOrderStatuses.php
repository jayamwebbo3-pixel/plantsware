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
        
        // 2. Automatically update shipped orders to delivered 48h after shipped_at
        $shippedToDeliveredCount = \App\Models\Order::autoUpdateShippedToDelivered();
        if ($shippedToDeliveredCount > 0) {
            $this->info("Automatically updated $shippedToDeliveredCount orders from shipped to delivered (48h dispatch window expired).");
        }
        
        $this->info("Finished updating order statuses. Completed " . count($expiredDeliveredOrders) . " returns and $shippedToDeliveredCount deliveries.");
    }
}
