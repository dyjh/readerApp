<?php

namespace App\Http\Resources\Store;

use Illuminate\Http\Resources\Json\Resource;

class CartResource extends Resource
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
            'id'              => $this->id,
            'product_book_id' => $this->product_book_id,
            'product_count'   => $this->product_count,
            'created_at'      => $this->created_at->toDateTimeString(),
            'name'            => $this->product->name,
            'sell_price'      => $this->product->sell_price,
            'stock'           => $this->product->stock,
            'on_sale'         => $this->product->on_sale,
            'cover'           => $this->product->cover,
        ];
    }

    public function with($request)
    {
        return [
            'status' => 1,
        ];
    }
}
