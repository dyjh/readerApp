<?php

namespace App\Services\store\Contracts;

use App\Models\stores\Contracts\OrderInterface;

abstract class AbstractOrderHandler
{
    /**
     * @param OrderInterface $order
     * @param OrderGeneratorInterface $orderGenerator
     */
    abstract public function beforeHandle(OrderInterface $order, OrderGeneratorInterface $orderGenerator);

    /**
     * @param OrderInterface $order
     * @param OrderGeneratorInterface $orderGenerator
     */
    abstract public function afterHandle(OrderInterface $order, OrderGeneratorInterface $orderGenerator);
}
