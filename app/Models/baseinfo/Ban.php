<?php

namespace App\Models\baseinfo;

use App\Models\BaseModel;
use App\Models\ScopeStatus;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ban extends BaseModel
{
    use SoftDeletes, ScopeStatus;

    protected $table = 'bans';
}
