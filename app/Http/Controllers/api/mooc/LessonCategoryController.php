<?php

namespace App\Http\Controllers\api\mooc;

use App\Http\Resources\mooc\BansResource;
use App\Http\Resources\mooc\GradesResource;
use App\Http\Resources\mooc\LessonCategoryResource;
use App\Http\Resources\mooc\SemestersResource;
use App\Models\baseinfo\Ban;
use App\Models\baseinfo\Grade;
use App\Models\baseinfo\Semester;
use App\Models\mooc\LessonCategory;
use Illuminate\Http\Request;

class LessonCategoryController extends MoocController
{
    /**
     * 科目列表
     */
    public function categories()
    {
        return $this->json(LessonCategoryResource::collection(LessonCategory::status()->get()));
    }

    /**
     * 年级列表
     */
    public function grades()
    {
        return $this->json(GradesResource::collection(Grade::status()->get()));
    }

    /*
     * 班级列表
     */
    public function bans()
    {
        return $this->json(BansResource::collection(Ban::status()->get()));
    }

    /**
     * 学期列表
     */
    public function semesters()
    {
        return $this->json(SemestersResource::collection(Semester::status()->get()));
    }
}
