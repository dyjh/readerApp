<?php

namespace App\Models\baseinfo;

use App\Models\BaseModel;
use App\Models\mooc\Lesson;
use App\Models\ScopeStatus;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends BaseModel
{
    use SoftDeletes, ScopeStatus;

    protected $table = 'teachers';

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
