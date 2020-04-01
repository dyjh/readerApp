<?php

namespace App\Models\mooc;

use App\Models\BaseModel;
use App\Models\mooc\traits\LessonedTrait;
use App\Models\ScopeStatus;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonCategory extends BaseModel
{
    use SoftDeletes;
    use ScopeStatus;
    use LessonedTrait;

    protected $table = 'lesson_categories';
}
