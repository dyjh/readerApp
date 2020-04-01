<?php

namespace App\Http\Resources\shares;

use Illuminate\Http\Resources\Json\Resource;

class PrivateBookResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $exists = $this->sharedBook->exists();
        return [
            'id' => $this->id,
            'shared_book_id' => $this->shared_book_id,
            'student_id' => $this->student_id,
            'rent_count' => $this->rent_count,
            'is_in_shelf' => $this->is_in_shelf,
            'name' => $this->when($exists, $this->sharedBook->name, null),
            'author' => $this->when($exists, $this->sharedBook->author, null),
            'cover' => $this->when($exists, $this->sharedBook->cover, null),
            'publisher' => $this->when($exists, $this->sharedBook->publisher, null),
            'isbn' => $this->when($exists, $this->sharedBook->isbn, null),
            'desc' => $this->when($exists, $this->sharedBook->desc, null),
            'rent_counts' => $this->when($exists, $this->sharedBook->rent_counts, null),
            'status' => $this->when($exists, $this->sharedBook->status, null),
        ];
    }
}
