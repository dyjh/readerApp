<?php

namespace App\Http\Resources\Store;

use Illuminate\Http\Resources\Json\Resource;

class OrderPostageResource extends Resource
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
            'order_id'       => $this->order_id,
            'name'           => $this->name,
            'price'          => $this->price,
            'express_number' => $this->express_number,
            'province'       => $this->province,
            'city'           => $this->city,
            'district'       => $this->district,
            'address'        => $this->address,
            'contact_name'   => $this->contact_name,
            'contact_number' => $this->contact_number,
        ];
    }
}
