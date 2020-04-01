<?php

namespace App\Http\Resources\mooc;

use App\Common\traits\APIResponseTrait;
use App\Http\Resources\mooc\LessonsResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LessonsCommentsCollection extends ResourceCollection
{
    use APIResponseTrait;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) {
                return LessonsCommentsResource::make($item);
            })
        ];
    }
}
