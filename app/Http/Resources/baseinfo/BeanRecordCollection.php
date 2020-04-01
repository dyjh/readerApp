<?php

namespace App\Http\Resources\baseinfo;

use App\Common\traits\APIResponseTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BeanRecordCollection extends ResourceCollection
{
    use APIResponseTrait;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->with($request);
        return [
            'data' => $this->collection->transform(function ($model) {
                return [
                    'id' => $model->id,
                    'changed_by' => $model->changed_by,
                    'amount' => $model->amount,
                    'changed_at' => $model->changed_at,
                ];
            })
        ];
    }
}
