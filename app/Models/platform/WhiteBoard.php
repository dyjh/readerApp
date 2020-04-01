<?php

namespace App\Models\platform;

use App\Models\BaseModel;

class WhiteBoard extends BaseModel
{
    protected $table = 'white_boards';

    protected $fillable = [
        'lesson_chapter_id',
        'uuid',
        'token',
        'name',
        'limit',
        'mode',
        'status'
    ];
}
