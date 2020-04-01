<?php

namespace App\Models\mooc;

use App\Http\Filters\Filterable;
use App\Models\baseinfo\Grade;
use App\Models\baseinfo\Semester;
use App\Models\baseinfo\Teacher;
use App\Models\BaseModel;
use App\Models\ScopeStatus;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends BaseModel
{
    use SoftDeletes, ScopeStatus, Filterable;

    protected $table = 'lessons';

    protected $fillable = [
        'teacher_id',
        'lesson_category_id',
        'lesson_type_id',
        'tag',
        'grade_id',
        'semester_id',
        'name',
        'desc',
        'price',
        'list_price',
        'sign_dead_line',
        'sign_count',
        'view_count',
        'lesson_hour_count',
        'is_streamed',
        'broadcast_day_begin',
        'broadcast_day_end',
        'broadcast_start_at',
        'broadcast_ent_at',
        'cover',
        'images',
        'preview_url',
        'rates'
    ];

    protected $casts = [
        'is_streamed' => 'boolean',
        'images' => 'array'
    ];

    // 所属教师
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id', 'id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'id');
    }

    // 科目
    public function category()
    {
        return $this->belongsTo(LessonCategory::class, 'lesson_category_id', 'id');
    }

    // 课程目录
    public function catalogs()
    {
        return $this->hasMany(LessonCatalog::class, 'lesson_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(LessonComment::class);
    }

    public function getReceivedAttribute()
    {
        return \Auth::user() && \Auth::user()->lessons()->where(['lesson_id'=> $this->getAttribute('id'), 'payment_statement'=> 1])->count();
    }

    public function scopeRecommend($query)
    {
        return $query->where('recommended', 1);
    }
}
