<?php

namespace App\Services\store\Contracts;

use App\Models\stores\Contracts\OrderInterface;

interface OrderGeneratorInterface
{
    /**
     * @return OrderInterface
     */
    public function generate(): OrderInterface;
}
