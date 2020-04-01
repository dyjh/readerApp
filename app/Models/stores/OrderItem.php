<?php

namespace App\Models\stores;

use App\Models\BaseModel;
use App\Models\HasOwner;

class OrderItem extends BaseModel
{
    use HasOwner;

    protected $table = 'order_items';

    protected $casts = [
        'student_id'    => 'integer',
        'order_id'      => 'integer',
        'product_id'    => 'integer',
        'product_name'  => 'string',
        'product_cover' => 'string',
        'product_price' => 'double',
        'product_count' => 'integer',
        'statement'     => 'integer',
        'total'         => 'double',
        'refund_no'     => 'string',
        'refund_method' => 'integer',
        'refund_reason' => 'string',
        'refund_remark' => 'string',
        'refund_request_at' => 'string',
        'refund_success_at' => 'string',
    ];

    protected $fillable = [
        'student_id',
        'order_id',
        'product_id',
        'product_name',
        'product_cover',
        'product_price',
        'product_count',
        'statement',
        'total',
        'refund_no',
        'refund_method',
        'refund_reason',
        'refund_remark',
        'refund_request_at',
        'refund_success_at',
    ];

    protected $dates = [
        'refund_request_at', 'refund_request_at'
    ];

    public function getStatusTextAttribute(): string
    {
        return trans('order.statuses_items.' . $this->getAttribute('statuses'));
    }

    public function product()
    {
        return $this->belongsTo(ProductBook::class,'product_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
