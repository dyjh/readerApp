<?php

namespace App\Http\Filters\Store;

use App\Http\Filters\Filter;
use App\Http\Requests\store\ProductBookRequest as Request;
use DB;

class ProductBookFilter extends Filter
{
    protected $filters = [
        'name',
        'category',
        'price_order',
        'sales_order',
        'new_order',
        'search',
    ];

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function category($value): void
    {
        $this->builder->where('product_category_id', $value);
    }

    public function name($name) :void
    {
        $this->builder->where('name', 'like', "%{$name}%");
    }

    public function price_order($value)
    {
        $direction = $value == 'up' ? 'asc' : 'desc';
        $this->builder->orderBy('sell_price', $direction);
    }

    public function sales_order($value)
    {
        $direction = $value == 'up' ? 'asc' : 'desc';
        $this->builder->orderBy('sales', $direction);
    }

    public function new_order($value)
    {
        $value && $this->builder->orderBy('created_at', 'desc');
    }

    public function search($value)
    {
        DB::statement("SET sql_mode = ''");
        $this->builder->search($value);
    }

}
