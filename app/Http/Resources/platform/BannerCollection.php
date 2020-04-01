<?php

namespace App\Http\Resources\platform;

use App\Common\traits\APIResponseTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BannerCollection extends ResourceCollection
{
    use APIResponseTrait;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => $item->type,
                    'cover' => $item->cover,
                    'href' => $item->href,
                    'lesson_id' => $item->recommend_lesson_id,
                    'sort' => $item->sort
                ];
            })
        ];
    }
}
