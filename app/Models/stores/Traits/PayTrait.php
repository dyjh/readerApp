<?php

namespace App\Models\stores\Traits;

use App\Models\stores\Order;

trait PayTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
