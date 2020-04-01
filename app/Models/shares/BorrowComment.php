<?php

namespace App\Models\shares;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class BorrowComment extends BaseModel
{
    use  SoftDeletes;

    protected $table = 'borrow_comments';

    protected $fillable = [
        'shared_book_id',
        'shared_book_name',
        'student_id',
        'student_name',
        'student_avatar',
        'content',
    ];
}
