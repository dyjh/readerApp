<?php

namespace App\Models\shares;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class SharedBook extends BaseModel
{
    use SoftDeletes;

    protected $table = 'shared_books';

    protected $fillable = [
        'school_id',
        'grade_id',
        'ban_id',
        'name',
        'author',
        'publisher',
        'isbn',
        'cover',
        'desc',
        'rent_counts',
    ];

    /**
     * 获取该类图书的所有者
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author marhone
     */
    public function privateBooks()
    {
        return $this->hasMany(PrivateBook::class, 'shared_book_id', 'id');
    }

    /**
     * 获取该图书的品论
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author marhone
     */
    public function comments()
    {
        return $this->hasMany(BorrowComment::class, 'shared_book_id', 'id');
    }
}
