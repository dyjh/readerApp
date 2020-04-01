<?php

namespace App\Http\Resources\Store;

use Illuminate\Http\Resources\Json\Resource;

class OrderItemResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'student_id'     => $this->student_id,
            'order_id'       => $this->order_id,
            'product_id'     => $this->product_id,
            'name'           => $this->product_name,
            'price'          => $this->product_price,
            'cover'          => $this->product_cover,
            'count'          => $this->product_count,
            'statement'      => $this->statement,
            'total'          => $this->total,
            'status_text'    => trans("order.statuses_items.$this->statement"),
            'refund_method'  => $this->refund_method,

            'refund_reason'  => $this->refund_reason,
            'refund_remark'  => $this->refund_remark,
            'refund_request_at'  => $this->refund_request_at,
            'refund_success_at'  => $this->refund_success_at,

        ];
    }
}
