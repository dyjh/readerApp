<?php

namespace App\Http\Resources\mooc;

use App\Http\Resources\User;
use Illuminate\Http\Resources\Json\Resource;

class LessonsCommentsResource extends Resource
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
            'id'         => $this->id,
            'content'    => $this->content,
            'created_at' => $this->created_at->toDateTimeString(),
            'user'       => new User($this->whenLoaded('user')),
            'rate'       => $this->rate,
        ];
    }
}
