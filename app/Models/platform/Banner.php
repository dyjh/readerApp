<?php

namespace App\Models\platform;

use App\Models\BaseModel;
use App\Models\mooc\Lesson;
use App\Models\ScopeStatus;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Banner extends BaseModel implements Sortable
{
    use ScopeStatus;
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort',
        'sort_when_creating' => true,
    ];

    public function recommendLesson()
    {
        return $this->belongsTo(Lesson::class,'recommend_lesson_id', 'id');
    }
}
