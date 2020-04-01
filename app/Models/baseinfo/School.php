<?php

namespace App\Models\baseinfo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use SoftDeletes;

    protected $casts = [
        'name' => 'string',
        'city' => 'string',
        'province' => 'string',
        'district' => 'string',
        'approach' => 'string',
        'special'  => 'string',
        'address'  => 'string',
        'telephone' => 'string',
        'school_type' => 'string',
        'status' => 'integer',
    ];

    protected $fillable = [
        'name', 'city', 'province', 'district', 'approach', 'special', 'address', 'telephone', 'school_type', 'status'
    ];
}
