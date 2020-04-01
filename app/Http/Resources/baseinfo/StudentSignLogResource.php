<?php

namespace App\Http\Resources\baseinfo;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

class StudentSignLogResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'month' => $this->month ?? Carbon::now()->format('Ym'),
            'is_signed' => $this->signed ?? false,
            'continue_days' => $this->continue_days ?? 0
        ];
    }
}
