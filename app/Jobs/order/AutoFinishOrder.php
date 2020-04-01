<?php

namespace App\Jobs\order;

use App\Models\stores\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AutoFinishOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 十五天前已发货待确认的订单
        $orders = Order::where('statement', 3)->where('updated_at', '<=', now()->addDays(-15))->select();

        ($orders->count()) && $orders->update(['statement' => 4]);
//        $orders->each(function (Order $order) {
//            $order->update(['statement' => 4]);
//        });
    }
}
