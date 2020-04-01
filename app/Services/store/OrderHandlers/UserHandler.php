<?php

namespace App\Services\store\OrderHandlers;

use App\Models\stores\Contracts\OrderInterface;
use App\Models\stores\Order;
use App\Models\baseinfo\Student;
use App\Services\store\Contracts\AbstractOrderHandler;
use App\Services\store\Contracts\OrderGeneratorInterface;

class UserHandler extends AbstractOrderHandler
{
    /**
     * @var Student
     */
    protected $user;

    public function __construct(Student $user)
    {
        $this->user = $user;
    }

    /**
     * @param OrderInterface|Order $order
     * @param OrderGeneratorInterface $orderGenerator
     */
    public function beforeHandle(OrderInterface $order, OrderGeneratorInterface $orderGenerator)
    {
        $order->user()->associate($this->user);
    }

    /**
     * @param OrderInterface|Order $order
     * @param OrderGeneratorInterface $orderGenerator
     */
    public function afterHandle(OrderInterface $order, OrderGeneratorInterface $orderGenerator)
    {
    }
}
