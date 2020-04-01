<?php

namespace App\Models\shares;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class SharedMessage extends BaseModel
{
    use SoftDeletes;

    protected $table = 'shared_messages';
}
