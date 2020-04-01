<?php

namespace App\Http\Resources\baseinfo;

use Illuminate\Http\Resources\Json\Resource;

class StudentResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->getAttribute('id'),
            'name' => $this->getAttribute('name'),
            'realname' => $this->getAttribute('realname'),
            'avatar' => $this->getAttribute('avatar'),
            'school_id' => $this->getAttribute('school_id'),
            'grade_id' => $this->getAttribute('grade_id'),
            'ban_id' => $this->getAttribute('ban_id'),
            'school_name' => $this->getAttribute('school_name'),
            'grade_name' => $this->getAttribute('grade_name'),
            'ban_name' => $this->getAttribute('ban_name'),
            'phone' => $this->getAttribute('phone'),
            'province' => $this->getAttribute('province'),
            'city' => $this->getAttribute('city'),
            'district' => $this->getAttribute('district'),
            'total_beans' => $this->getAttribute('total_beans'),
            'read_count' => $this->getAttribute('read_count'),
            'share_count' => $this->getAttribute('share_count')
        ];
    }
}
