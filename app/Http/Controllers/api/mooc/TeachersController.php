<?php

namespace App\Http\Controllers\api\mooc;

use App\Http\Resources\mooc\TeachersResource;
use App\Models\baseinfo\Teacher;

class TeachersController extends MoocController
{
    /**
     * 讲师列表
     */
    public function index()
    {
        return $this->json(TeachersResource::collection(Teacher::status()->get()));
    }

    /**
     * 讲师课程列表
     * @param Teacher $teacher
     * @return \Illuminate\Http\JsonResponse
     */
    public function lessons(Teacher $teacher)
    {
        $lessons = $teacher->lessons()->select(['id','name','price','cover','view_count','sign_count'])->get();

        return $this->json($lessons);
    }
}
