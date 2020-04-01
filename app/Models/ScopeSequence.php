<?php

namespace App\Models;

trait ScopeSequence
{
    public function scopeSort($query)
    {
        return $query->orderBy('sequence', 'asc');
    }
}
