<?php

namespace App\Models\shares;

use App\Common\Definition;
use App\Models\baseinfo\Student;
use App\Models\BaseModel;

class PrivateBook extends BaseModel
{
    protected $table = 'private_books';

    protected $fillable = [
        'shared_book_id',
        'student_id',
        'rent_count',
        'is_in_shelf'
    ];

    protected $casts = [
        'is_in_shelf' => 'boolean'
    ];

    /**
     * 共享书籍
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author marhone
     */
    public function sharedBook()
    {
        return $this->belongsTo(SharedBook::class, 'shared_book_id', 'id');
    }

    /**
     * 书籍持有者
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author marhone
     */
    public function owner()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    // 借阅中
    public function lending()
    {
        return $this
            ->hasMany(StudentsBooksRent::class, 'private_book_id', 'id')
            ->where('statement', Definition::SHARED_BOOK_RENT_STATE_RENTING);
    }

    public function rented()
    {
        return $this
            ->hasMany(StudentsBooksRent::class, 'private_book_id', 'id')
            ->where('statement', Definition::SHARED_BOOK_RENT_STATE_RETURNED);
    }

    // 书籍上架
    public function putaway()
    {
        $this->setAttribute('is_in_shelf', true);
        return $this->save();
    }

    // 书籍下架
    public function recycle()
    {
        $this->setAttribute('is_in_shelf', false);
        return $this->save();
    }
}
