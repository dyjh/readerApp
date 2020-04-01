<?php


namespace App\Repositories\shares;


use App\Models\shares\PrivateBook;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Validator\Contracts\ValidatorInterface;

class PrivateBookRepository extends BaseRepository
{
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'shared_book_id' => 'required|exists:shared_books,id',
            'student_id' => 'required|exists:students,id',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'shared_book_id' => 'nullable|exists:shared_books,id',
            'student_id' => 'nullable|exists:students,id',
        ]
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PrivateBook::class;
    }

    public function boot()
    {
        // 路由上的查询标准
        $this->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
    }
}