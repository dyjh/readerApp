<?php

namespace App\Http\Resources\mooc;

use Illuminate\Http\Resources\Json\Resource;

class TeachersResource extends Resource
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
            'id'      => $this->id,
            'name'    => $this->name,
            'avatar'  => $this->avatar,
            'title'   => $this->title,
            'profile' => $this->profile,
        ];
    }
}
