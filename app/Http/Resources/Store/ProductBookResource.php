<?php

namespace App\Http\Resources\Store;

use Illuminate\Http\Resources\Json\Resource;

class ProductBookResource extends Resource
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
            'id'          => $this->id,
            'cover'       => $this->cover,
            'author'      => $this->author,
            'name'        => $this->name,
            'publisher'   => $this->publisher,
            'isbn'        => $this->isbn,
            'tag_price'   => $this->tag_price,
            'discount'    => $this->discount,
            'sell_price'  => $this->sell_price,
            'sales'       => $this->sales,
            'rates'       => $this->rates,
            'stock'       => $this->stock,
            'videos'      => $this->videos,
            'info_text'   => $this->info_text,
            'content'     => $this->content,
            'info_images'    => $this->info_images,
            'comment_counts' => $this->comment_counts,
            'product_category_id' => $this->product_category_id,
        ];
    }

    public function with($request)
    {
        return [
            'status' => 1,
        ];
    }
}
