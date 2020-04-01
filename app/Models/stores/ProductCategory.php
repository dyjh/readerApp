<?php

namespace App\Models\stores;

use App\Models\ScopeStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use SoftDeletes, ScopeStatus;

}
