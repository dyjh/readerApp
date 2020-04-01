<?php


namespace App\Models\mooc\traits;


use App\Models\mooc\Lesson;

trait LessonedTrait
{
    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'id');
    }
}