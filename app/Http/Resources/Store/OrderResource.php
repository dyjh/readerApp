<?php

namespace App\Http\Resources\Store;

use Illuminate\Http\Resources\Json\Resource;

class OrderResource extends Resource
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
            'trade_no'       => $this->trade_no,
            'title'          => $this->title,
            'total'          => floatval($this->total),
            'tag_price'      => $this->tag_price,
            'statement'      => $this->statement,
            'total_amount'   => $this->total_amount,
            'status_text'    => trans("order.statuses.$this->statement"),
            'pay_type'       => trans("order.pay_type.$this->payment_method"),
            'paid_at'        => $this->paid_at,
            'created_at'     => $this->created_at->toDateTimeString(),
            'items'          => OrderItemResource::collection($this->whenLoaded('orderItems')),
            'postage'        => new OrderPostageResource($this->whenLoaded('postage')),
        ];
    }

    public function with($request)
    {
        return [
            'status' => 1,
        ];
    }
}
