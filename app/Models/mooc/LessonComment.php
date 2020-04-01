<?php

namespace App\Models\mooc;

use App\Models\BaseModel;
use App\Models\HasOwner;
use App\Models\mooc\traits\LessonedTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonComment extends BaseModel
{
    use SoftDeletes, HasOwner, LessonedTrait;

    protected $table = 'lesson_comments';

    protected $fillable = [
        'lesson_id',
        'student_id',
        'student_avatar',
        'rate',
        'content',
    ];
}
