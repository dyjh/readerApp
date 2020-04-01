<?php

namespace App\Http\Resources\shares;

use App\Common\traits\APIResponseTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PrivateBookCollection extends ResourceCollection
{
    use APIResponseTrait;

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            'data' => $this->collection->transform(function ($model) {
                $exists = $model->sharedBook->exists();
                return [
                    'id' => $model->id,
                    'shared_book_id' => $model->shared_book_id,
                    'student_id' => $model->student_id,
                    'rent_count' => $model->rent_count,
                    'is_in_shelf' => $model->is_in_shelf,
                    'name' => $this->when($exists, $model->sharedBook->name, null),
                    'author' => $this->when($exists, $model->sharedBook->author, null),
                    'cover' => $this->when($exists, $model->sharedBook->cover, null),
                    'publisher' => $this->when($exists, $model->sharedBook->publisher, null),
                    'isbn' => $this->when($exists, $model->sharedBook->isbn, null),
                    'desc' => $this->when($exists, $model->sharedBook->desc, null),
                    'rent_counts' => $this->when($exists, $model->sharedBook->rent_counts, null),
                    'status' => $this->when($exists, $model->sharedBook->status, null),
                ];
            })
        ];
    }
}
