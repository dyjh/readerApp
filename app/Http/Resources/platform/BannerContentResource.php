<?php

namespace App\Http\Resources\platform;

use Illuminate\Http\Resources\Json\Resource;

class BannerContentResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'content' => $this->content,
        ];
    }
}
