<?php

namespace App\Models\stores;

use App\Models\BaseModel;
use App\Models\HasOwner;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductBookComment extends BaseModel
{
    use SoftDeletes, HasOwner;

    protected $table = 'product_book_comments';

    protected $casts = [
        'order_id'          => 'integer',
        'product_book_id'   => 'integer',
        'student_id'        => 'integer',
        'desc_match_rate'   => 'string',
        'content'           => 'string',
        'service_attitude_rate' => 'string',

    ];

    protected $fillable = [
        'order_id', 'product_book_id', 'student_id', 'desc_match_rate', 'service_attitude_rate', 'content',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(ProductBook::class,'product_book_id');
    }
}
