<?php

namespace App\Http\Controllers\api\mooc;

use App\Common\ResponseStatement;
use App\Http\Filters\Mooc\LessonFilter;
use App\Http\Resources\mooc\CatalogsResource;
use App\Http\Resources\mooc\LessonsResource;
use App\Http\Resources\mooc\LessonsCollection;
use App\Models\mooc\Lesson;
use App\Models\mooc\LessonChapter;
use App\Models\mooc\StudentLessonHistory;
use Illuminate\Http\Request;

class LessonsController extends MoocController
{
    /**
     * 课程列表
     * @param LessonFilter $filter
     */
    public function index(LessonFilter $filter)
    {
        return LessonsCollection::make(Lesson::with(['teacher'])->filter($filter)->status()->paginate());
    }

    /**
     * 推荐课程列表
     */
    public function recommends()
    {
        return LessonsCollection::make(Lesson::with(['teacher'])->status()->recommend()->take(10)->latest()->get());
    }

    /**
     * 详情
     * @param Lesson $lesson
     */
    public function show(Lesson $lesson)
    {
        $lesson->load(['teacher']);
        $lesson->increment('view_count');

        return $this->json(LessonsResource::make($lesson));
    }

    /**
     * 课程目录
     * @param Lesson $lesson
     */
    public function catalogs(Lesson $lesson)
    {
        return $this->json(CatalogsResource::collection($lesson->catalogs()->sort()->with(['chapters' => function ($query) {$query->sort();}])->get()));
    }

    /**
     * 课程观看记录
     * @param LessonChapter $lessonChapter
     * @return \Illuminate\Http\JsonResponse
     */
    public function watchLesson(LessonChapter $lessonChapter)
    {
        $user = auth('api')->user();
        $insertData = [
            'student_id'        => $user->id,
            'lesson_id'         => $lessonChapter->lesson_id,
            'lesson_chapter_id' => $lessonChapter->id,
            'name'              => $lessonChapter->name,
            'watched_at'        => date('Y-m-d H:i:s'),
            'watched_minutes'   => 10
        ];
        $record = StudentLessonHistory::create($insertData);
        if ($record) {
            return $this->json();
        } else {
            return $this->json('添加记录失败', ResponseStatement::STATUS_ERROR);
        }

    }
}
