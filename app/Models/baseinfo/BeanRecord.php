<?php

namespace App\Models\baseinfo;

use App\Models\BaseModel;

class BeanRecord extends BaseModel
{
    protected $table = 'bean_records';

    protected $fillable = [
        'student_id',
        'changed_by',
        'amount',
        'changed_at',
        'before_beans_total',
        'after_beans_total',
        'trans_no',
        'trade_no'
    ];

    public function owner()
    {
        return $this->belongsTo(Student::class, 'student_id',  'id');
    }
}
