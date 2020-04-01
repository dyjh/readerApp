<?php

namespace App\Http\Resources\shares;

use App\Common\traits\APIResponseTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SharedBookOwnerCollection extends ResourceCollection
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
        $this->with($request);
        return [
            'data' => $this->collection->transform(function ($model) {
                $hasOwner = $model->owner()->exists();
                return [
                    'id' => $model->id,
                    'shared_book_id' => $model->shared_book_id,
                    'rent_count' => $model->rent_count,
                    'is_in_shelf' => $model->is_in_shelf,
                    'is_renting' => $this->when($model->lending()->exists(), true, false),
                    'owner_id' => $this->when($hasOwner, $model->owner->id),
                    'name' => $this->when($hasOwner, $model->owner->name),
                    'avatar' => $this->when($hasOwner, $model->owner->avatar),
                ];
            })
        ];
    }
}
