<?php

namespace App\Services\store\OrderHandlers;

use App\Models\stores\Contracts\OrderInterface;
use App\Models\stores\Order;
use App\Models\baseinfo\Student;
use App\Services\store\Contracts\AbstractOrderHandler;
use App\Services\store\Contracts\OrderGeneratorInterface;

class OrderHandler extends AbstractOrderHandler
{

    /**
     * @param OrderInterface|Order $order
     * @param OrderGeneratorInterface $orderGenerator
     */
    public function beforeHandle(OrderInterface $order, OrderGeneratorInterface $orderGenerator)
    {
        $order->setAttribute('trade_no', date('YmdHis') . str_pad(mt_rand(1, 99), 2, '0', STR_PAD_LEFT));
    }

    /**
     * @param OrderInterface|Order $order
     * @param OrderGeneratorInterface $orderGenerator
     */
    public function afterHandle(OrderInterface $order, OrderGeneratorInterface $orderGenerator)
    {
    }
}
