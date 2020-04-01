<?php

namespace App\Models\stores\Observers;

use App\Models\stores\Order;
use Illuminate\Http\Request;

class OrderObserver
{
    public function created(Order $order)
    {
    }

    public function updated(Order $order)
    {
//        if ($order->isDirty('status')) {
//
//            $currentStatus = $order->getAttribute('status');
//            $notificationClass = 'App\\Notifications\\store\\Order' . ucfirst($currentStatus);
//            class_exists($notificationClass) && $order->user->notify(new $notificationClass($order));
//        }
    }

}
