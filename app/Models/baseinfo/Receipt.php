<?php

namespace App\Models\baseinfo;

use App\Models\HasOwner;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasOwner;

    protected $fillable = [
        'id',
        'payable_id',
        'payable_type',
        'student_id',
        'platform',
        'type',
        'title',
        'trade_no',
        'total',
        'original',
        'expired_at',
        'paid_at'
    ];

    protected $dates = [
        'paid_at', 'expired_at'
    ];

    public function payable()
    {
        return $this->morphTo();
    }
}
