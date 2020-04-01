<?php

namespace App\Http\Resources\mooc;

use App\Common\traits\APIResponseTrait;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LessonHistoriesResource extends ResourceCollection
{
    use APIResponseTrait;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($model) {
                return [
                    'id'                => $model->id,
                    'name'              => $model->name,
                    'lesson_chapter_id' => $model->lesson_chapter_id,
                    'lesson_id'         => $model->lesson_id,
                    'watched_minutes'   => $model->watched_minutes,
                    'created_at'        => $model->created_at->toDateTimeString(),
                ];
            })
        ];

    }
}
