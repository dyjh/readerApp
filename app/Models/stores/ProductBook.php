<?php

namespace App\Models\stores;

use App\Models\BaseModel;
use App\Http\Filters\Filterable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductBook extends BaseModel
{
    use SoftDeletes, Filterable;

    protected $table = 'product_books';

    protected $casts = [
        'name' => 'string',
        'author' => 'string',
        'publisher' => 'string',
        'isbn' => 'string',
        'videos' => 'array',
        'info_images'  => 'array',
        'info_text'  => 'string',
        'stock' => 'integer',
        'tag_price' => 'double',
        'discount' => 'double',
        'sell_price' => 'double',
        'rates' => 'double',
        'sales' => 'integer',
        'comment_counts' => 'integer',
        'product_category_id' => 'integer',
    ];

    protected $fillable = [
        'name', 'author', 'publisher', 'isbn', 'videos', 'info_images', 'info_text', 'stock',
        'tag_price', 'discount', 'sell_price', 'rates', 'sales', 'comment_counts','product_category_id'
    ];

    public function getVideosAttribute() :array
    {
        $videos = json_decode($this->attributes['videos'], true);
        $videos['preimage'] = isset($videos['preimage']) ? starts_with($videos['preimage'], 'http') ? $videos['preimage'] : \Storage::disk('qiniu')->url($videos['preimage']) : '';;
        $videos['prevideo'] = isset($videos['prevideo']) ? starts_with($videos['prevideo'], 'http') ? $videos['prevideo'] : \Storage::disk('qiniu')->url($videos['prevideo']) : '';
        return $videos;
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class,'product_category_id');
    }

    public function scopeSale($query)
    {
        return $query->where('on_sale', 1);
    }


    public function scopeSort($query)
    {
        return $query->orderBy('rates', 'DESC');
    }

    public function comments()
    {
        return $this->hasMany(ProductBookComment::class);
    }
}
