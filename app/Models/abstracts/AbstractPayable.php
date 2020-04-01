<?php

namespace App\Models\abstracts;

use App\Models\baseinfo\Receipt;
use App\Models\BaseModel;
use App\Models\interfaces\PayableInterface;

abstract class AbstractPayable extends BaseModel implements PayableInterface
{
    protected $with = [
        'receipts'
    ];

    public function receipt()
    {
        return $this->morphOne(Receipt::class, 'payable');
    }

    public function receipts()
    {
        return $this->morphMany(Receipt::class, 'payable');
    }

    public function isPaid()
    {
        return $this->receipts->contains('paid', true);
    }
}
