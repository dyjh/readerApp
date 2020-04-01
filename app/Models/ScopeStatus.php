<?php

namespace App\Models;

trait ScopeStatus
{
    public function scopeStatus($query)
    {
        return $query->where('status', 1);
    }
}
