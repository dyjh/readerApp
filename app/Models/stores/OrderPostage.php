<?php

namespace App\Models\stores;

use Illuminate\Database\Eloquent\Model;

class OrderPostage extends Model
{
    protected $casts = [
        'order_id' => 'integer',
        'name' => 'string',
        'price' => 'string',
        'express_number' => 'string',
        'province' => 'string',
        'city' => 'array',
        'district'  => 'array',
        'address'  => 'string',
        'contact_name'  => 'string',
        'contact_number'  => 'string',
    ];

    protected $fillable = [
        'name', 'price', 'express_number', 'province', 'city', 'district',
        'address', 'contact_name', 'contact_number', 'order_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
