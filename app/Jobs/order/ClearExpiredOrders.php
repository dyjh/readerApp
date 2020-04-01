<?php

namespace App\Jobs\order;

use App\Events\Order\OrderCancel;
use App\Models\stores\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ClearExpiredOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $orders = Order::where('statement', 1)->where('updated_at', '<=', now()->addMinutes(-30))->get();

        $orders->each(function (Order $order) {

            $order->update(['statement' => 8]);

            OrderCancel::dispatch($order);
        });
    }
}
