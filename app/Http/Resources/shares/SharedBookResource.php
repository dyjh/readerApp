<?php

namespace App\Http\Resources\shares;

use Illuminate\Http\Resources\Json\Resource;

class SharedBookResource extends Resource
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
            'id' => $this->id,
            'school_id' => $this->school_id,
            'ban_id' => $this->ban_id,
            'name' => $this->name,
            'author' => $this->author,
            'publisher' => $this->publisher,
            'isbn' => $this->isbn,
            'cover' => $this->cover,
            'desc' => $this->desc,
            'rent_counts' => $this->rent_counts,
            // 'owners' => $this->owners(),
            // 'comments' => $this->comments,
        ];
    }
}
