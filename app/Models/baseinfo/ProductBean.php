<?php

namespace App\Models\baseinfo;

use App\Models\BaseModel;
use App\Models\ScopeStatus;

class ProductBean extends BaseModel
{
    use ScopeStatus;

    protected $table = 'product_beans';

    protected $fillable = [
        'name',
        'amount',
        'price'
    ];
}
