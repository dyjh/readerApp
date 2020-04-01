<?php

namespace App\Models\stores;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'student_id'      => 'integer',
        'product_book_id' => 'integer',
        'product_count'   => 'integer',
        'name'            => 'string',
        'cover'           => 'string',
        'sell_price'      => 'double',
    ];

    protected $fillable = [
        'name', 'cover', 'sell_price', 'student_id', 'student_id', 'product_book_id', 'product_count'
    ];

    protected $table = 'carts';

    public function product()
    {
        return $this->belongsTo(ProductBook::class,'product_book_id');
    }

    public function scopeSort($query)
    {
        return $query->orderBy('created_at', 'DESC');
    }
}
