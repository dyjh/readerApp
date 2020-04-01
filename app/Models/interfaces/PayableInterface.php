<?php

namespace App\Models\interfaces;

use Carbon\Carbon;

interface PayableInterface
{
    public function getTotal(): float;

    public function getTitle(): string;

    public function getStudentId(): int;

    public function getTradeNo(): string;

    /**
     * @return Carbon
     */
    public function getExpiredAt(): Carbon;

    /**
     * @return integer
     */
    public function getExpiredMinutes(): int;
}
