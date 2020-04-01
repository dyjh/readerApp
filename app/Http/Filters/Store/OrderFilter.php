<?php

namespace App\Http\Filters\Store;

use App\Http\Filters\Filter;

class OrderFilter extends Filter
{
    protected $filters = [
        'status',
    ];

    public function status($value) : void
    {
        $this->builder->where('statement', $value);
    }

}
