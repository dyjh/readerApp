<?php

namespace App\Models\stores\Traits;

trait OrderItemRelation
{
    
    /**
     * 商品名称
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->getAttribute('name');
    }

    /**
     * 获取单价
     * @return float
     */
    public function getPrice(): float
    {
        return (float)$this->getAttribute('price');
    }

    /**
     * 根据数量获得总价
     * @param int $count
     * @return float
     */
    public function calcTotal(int $count): float
    {
        return floatval(bcmul($this->getPrice(), $count, 2));
    }
}
