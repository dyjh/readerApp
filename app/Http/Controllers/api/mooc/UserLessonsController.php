<?php

namespace App\Http\Controllers\api\mooc;

use App\Http\Resources\mooc\LessonHistoriesResource;
use App\Models\mooc\LessonChapter;
use App\Repositories\mooc\LessonRepositoryEloquent;

class UserLessonsController extends MoocController
{
    private $repository;

    public function __construct(LessonRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 用户课程列表
     */
    public function user_lessons()
    {
        $lessons = $this->repository->user_lessons();

        return $this->json($lessons);
    }

    /**
     * 课程观看记录列表
     */
    public function lesson_records()
    {
        return LessonHistoriesResource::make(\Auth::user() ? \Auth::user()->lessons_histories()->latest()->take(10)->get() : collect());
    }

    /**
     * 记录课程小节
     * @param LessonChapter $chapter
     */
    public function lesson_record(LessonChapter $chapter)
    {

    }
}
