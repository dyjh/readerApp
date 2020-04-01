<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

/**
 * Class BaseModel
 * @package App\Models
 * @author marhone
 */
class BaseModel extends Model
{
    public function getCoverAttribute($value): string
    {
        return $this->getUrl($value);
    }

    public function getAvatarAttribute($value): string
    {
        return $this->getUrl($value);
    }

    public function getInfoImagesAttribute($value) :array
    {
        $images = json_decode($value, true);
        if (is_array($images)) {
            return array_map(function ($value){
                return $this->getUrl($value);
            }, $images);
        }
        return [];
    }

    public function getImagesAttribute($value) :array
    {
        $images = json_decode($value, true);
        if (is_array($images)) {
            return array_map(function ($value){
                return $this->getUrl($value);
            }, $images);
        }
        return [];
    }

    public function getPrevideoAttribute($value): string
    {
        return $this->getUrl($value);
    }

    public function getPreimageAttribute($value): string
    {
        return $this->getUrl($value);
    }

    protected function getUrl($value) :string
    {
        return $value ? starts_with($value, 'http') ? $value : Storage::disk('qiniu')->url($value) : '';
    }

}
