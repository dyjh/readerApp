<?php

namespace App\Http\Resources\mooc;

use Illuminate\Http\Resources\Json\Resource;

class LessonsResource extends Resource
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
            'name'           => $this->name,
            'tag'            => $this->tag,
            'desc'           => $this->desc,
            'price'          => floatval($this->price),
            'list_price'     => floatval($this->list_price),
            'sign_dead_line' => $this->sign_dead_line,
            'sign_count'     => $this->sign_count,
            'view_count'     => $this->view_count,
            'is_streamed'    => $this->is_streamed,
            'lesson_hour_count'   => $this->lesson_hour_count,
            'broadcast_day_begin' => $this->broadcast_day_begin,
            'broadcast_day_end'   => $this->broadcast_day_end,
            'broadcast_start_at'  => date('H:i', strtotime($this->broadcast_start_at)),
            'broadcast_ent_at'    => date('H:i', strtotime($this->broadcast_ent_at)),
            'prevideo'      => $this->prevideo,
            'preimage'      => $this->preimage,
            'cover'         => $this->cover,
            'images'        => $this->images,
            'rates'         => $this->rates,
            'received'      => $this->received,
            'teacher'       => new TeachersResource($this->whenLoaded('teacher')),
        ];
    }
}
