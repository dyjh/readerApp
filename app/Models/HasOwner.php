<?php

namespace App\Models;

use App\Models\baseinfo\Student;
use Illuminate\Database\Eloquent\Builder;

trait HasOwner
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(Student::class,'student_id');
    }

    public function scopeWhose(Builder $query, int $userId)
    {
        return $query->where('student_id', '=', $userId);
    }
}
