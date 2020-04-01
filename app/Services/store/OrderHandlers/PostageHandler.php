<?php

namespace App\Services\store\OrderHandlers;

use App\Models\stores\OrderPostage;
use App\Models\stores\Order;
use App\Models\stores\Contracts\OrderInterface;
use App\Services\store\Contracts\AbstractOrderHandler;
use App\Services\store\Contracts\OrderGeneratorInterface;

class PostageHandler extends AbstractOrderHandler
{

    protected $postage;

    /**
     * @var OrderPostage
     */
    protected $orderPostage;

    public function __construct(array $postage)
    {
        $this->postage = $postage;
    }

    /**
     * @param OrderInterface|Order $order
     * @param OrderGeneratorInterface $orderGenerator
     */
    public function beforeHandle(OrderInterface $order, OrderGeneratorInterface $orderGenerator)
    {
        $this->orderPostage = new OrderPostage($this->postage);
        #$order->setAttribute('total', bcadd($order->total, $this->orderPostage->price, 2));
    }

    /**
     * @param OrderInterface|Order $order
     * @param OrderGeneratorInterface $orderGenerator
     */
    public function afterHandle(OrderInterface $order, OrderGeneratorInterface $orderGenerator)
    {
        $order->setRelation('postage', $order->postage()->save($this->orderPostage));
    }
}
