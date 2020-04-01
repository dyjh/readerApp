<?php

namespace App\Services\store;

use App\Models\stores\Contracts\OrderInterface;
use App\Models\stores\Order;
use App\Services\store\Contracts\AbstractOrderGenerator;

class OrderGenerator extends AbstractOrderGenerator
{
    /**
     * @return Order
     * @throws \Throwable
     */
    public function generate(): OrderInterface
    {
        $this->setDefault();

        return parent::generate();
    }

    protected function setDefault()
    {
        $this->order->fill([
            'student_id' => 0,
            'trade_no'   => 0,
            'total'      => 0,
            'title'      => '',
            'statement'  => 1,
            'tag_price'  => 0,
            'total_amount' => 0,
        ]);
    }
}
