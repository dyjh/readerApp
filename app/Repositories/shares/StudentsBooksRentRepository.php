<?php


namespace App\Repositories\shares;


use App\Models\shares\StudentsBooksRent;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Validator\Contracts\ValidatorInterface;

class StudentsBooksRentRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'statement' => '='
    ];

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'private_book_id' => 'required|exists:private_books,id',
            'renter_id' => 'required|exists:students,id',
            'renter_name' => 'required|max:20',
            'lender_id' => 'required|exists:students,id',
            'lender_name' => 'required|max:20',
            'shared_book_id' => 'required|exists:shared_books,id',
            'shared_book_name' => 'required|max:50',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'private_book_id' => 'nullable|exists:private_books,id',
            'renter_id' => 'nullable|exists:students,id',
            'renter_name' => 'nullable|max:20',
            'lender_id' => 'nullable|exists:students,id',
            'lender_name' => 'required|max:20',
            'shared_book_id' => 'nullable|exists:shared_books,id',
            'shared_book_name' => 'nullable|max:50',
        ]
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return StudentsBooksRent::class;
    }

    public function boot()
    {
        $this->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
    }
}