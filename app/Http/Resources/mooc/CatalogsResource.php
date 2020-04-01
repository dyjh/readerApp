<?php

namespace App\Http\Resources\mooc;

use Illuminate\Http\Resources\Json\Resource;

class CatalogsResource extends Resource
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
            'id'        => $this->id,
            'name'      => $this->name,
            'desc'      => $this->desc,
            'chapters'  => ChaptersResource::collection($this->whenLoaded('chapters')),
        ];
    }
}
