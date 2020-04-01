<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderCancel;
use App\Models\stores\OrderItem;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateProductStock implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  OrderCancel  $event
     * @return void
     */
    public function handle(OrderCancel $event)
    {
        $event->order->orderItems->each(function (OrderItem $item){
            
            $item->product->increment('stock', $item->product_count);
            
        });
    }
}
