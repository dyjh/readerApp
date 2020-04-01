<?php

namespace App\Http\Filters\Store;

use App\Http\Filters\Filter;
use App\Http\Requests\store\CartRequest as Request;

class CartFilter extends Filter
{
    protected $filters = [
        'book',
    ];

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function book($value)
    {
    }

}
