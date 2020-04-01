<?php

namespace App\Http\Resources\mooc;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

class ChaptersResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $surplus_day = Carbon::now()->diffInDays($this->broadcast_day,false);
        $broadcast_week = Carbon::parse($this->broadcast_day)->dayOfWeekIso;
        return [
            'id'                 => $this->id,
            'lesson_id'          => $this->lesson_id,
            'name'               => $this->name,
            'broadcast_day'      => $this->broadcast_day,
            'broadcast_start_at' => $this->broadcast_start_at,
            'broadcast_ent_at'   => $this->broadcast_ent_at,
            'play_back_url'      => $this->play_back_url,
            'is_streamed'        => $this->is_streamed,
            'stream_url'         => $this->stream_url,
            'teacher_name'       => $this->lesson->teacher->name,
            'broadcast_week'     => trans("lesson.broadcast_week.$broadcast_week"),
            'surplus_day'        => ($surplus_day > 0) ? $surplus_day : 0,
            'chat_room'          => json_decode($this->chat_room, true),
            'live_status'        => $this->live_status,
        ];
    }
}
