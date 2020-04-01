<?php

namespace App\Http\Resources\shares;

use App\Common\traits\APIResponseTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SharedBookCollection extends ResourceCollection
{
    use APIResponseTrait;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
