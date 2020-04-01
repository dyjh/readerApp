<?php

namespace App\Http\Controllers\api\mooc;

use App\Events\Mooc\LessonCommented;
use App\Http\Requests\mooc\CreateLessonComment;
use App\Http\Resources\mooc\LessonsCommentsCollection;
use App\Models\mooc\Lesson;
use App\Services\mooc\Exceptions\LessonGeneratorException;
use DB;

class LessonsCommentsController extends MoocController
{
    /**
     * 课程评论列表
     * @param Lesson $lesson
     * @return LessonsCommentsCollection
     */
    public function index(Lesson $lesson)
    {
        return LessonsCommentsCollection::make($lesson->comments()->with(['user'])->latest()->paginate());
    }

    /**
     * 用户评价
     * @param CreateLessonComment $request
     * @param Lesson $lesson
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(CreateLessonComment $request, Lesson $lesson)
    {
        throw_if(($lesson->received ==0), LessonGeneratorException::class, 40018);

        DB::beginTransaction();

        $comment = $lesson->comments()->create([
            'student_id'      => \Auth::id(),
            'content'         => $request->get('content', ''),
            'rate'            => $request->get('rate', 5),
        ]);

        event(new LessonCommented($comment));

        DB::commit();

        return $this->json();
    }
}
