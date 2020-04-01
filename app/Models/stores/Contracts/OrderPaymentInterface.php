<?php

namespace App\Models\stores\Contracts;

interface OrderPaymentInterface
{
    /**
     * 获取单价
     * @return float
     */
    public function getPrice(): float;

    /**
     * 根据数量获得总价值
     * @param int $quantity
     * @return float
     */
    public function calcTotal(int $quantity): float;

    /**
     * 是否有足够可用的量
     * @param int $quantity
     * @return bool
     */
    public function isAvailable(int $quantity): bool;
}
