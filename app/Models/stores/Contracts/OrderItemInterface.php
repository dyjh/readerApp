<?php

namespace App\Models\stores\Contracts;

interface OrderItemInterface
{
    /**
     * 商品名称
     * @return string
     */
    public function getName(): string;

    /**
     * 商品名称
     * @return string
     */
    public function getCover(): string;

    /**
     * 获取单价
     * @return float
     */
    public function getPrice(): float;

    /**
     * 根据数量获得总价
     * @param int $quantity
     * @return float
     */
    public function calcTotal(int $quantity): float;
}
