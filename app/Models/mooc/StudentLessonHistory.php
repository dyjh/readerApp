<?php

namespace App\Models\mooc;

use App\Models\BaseModel;
use App\Models\HasOwner;
use App\Models\mooc\traits\LessonedTrait;

class StudentLessonHistory extends BaseModel
{
    use HasOwner, LessonedTrait;

    protected $table = 'student_lesson_histories';

    protected $fillable = [
        'student_id',
        'lesson_id',
        'lesson_chapter_id',
        'name',
        'watched_at',
        'watched_minutes',
    ];
}
