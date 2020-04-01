<?php

namespace App\Models\stores\Contracts;

interface OrderInterface
{
    public function setAttribute($key, $value);

    public function getAttribute($key);

    public function fill(array $attributes);

    public function save(array $options = []);
}
