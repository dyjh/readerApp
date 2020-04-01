<?php

namespace App\Http\Filters\Mooc;

use App\Http\Filters\Filter;
use App\Http\Requests\mooc\LessonRequest as Request;
use DB;

class LessonFilter extends Filter
{
    protected $filters = [
        'name',
        'category',
        'streamed',
        'grade',
        'semester',
        'tag',
        'search',
    ];

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function streamed($value): void
    {
        $this->builder->where('is_streamed', $value ?? 1);
    }

    public function grade($value): void
    {
        $this->builder->where('grade_id', $value);
    }

    public function semester($value): void
    {
        $this->builder->where('semester_id', $value);
    }

    public function category($value): void
    {
        $this->builder->where('lesson_category_id', $value);
    }

    public function name($name) :void
    {
        $this->builder->where('name', 'like', "%{$name}%");
    }

    public function tag($value)
    {
        $this->builder->where('tag', $value);
       // $direction = $value == 'up' ? 'asc' : 'desc';
        $this->builder->orderByDesc('created_at');
    }

    public function search($value)
    {
        DB::statement("SET sql_mode = ''");
        $this->builder->search($value);
    }

}
