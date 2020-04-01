<?php


namespace App\Services\shares;


use App\Common\Definition;
use App\Criteria\common\EnabledCriteria;
use App\Events\shares\SharedBookReturnedEvent;
use App\Exceptions\api\shares\RentBookException;
use App\Models\baseinfo\Student;
use App\Models\shares\PrivateBook;
use App\Models\shares\SharedBook;
use App\Models\shares\StudentsBooksRent;
use App\Repositories\baseinfo\StudentRepository;
use App\Repositories\shares\PrivateBookRepository;
use App\Repositories\shares\StudentsBooksRentRepository;
use Illuminate\Support\Carbon;

class StudentsBooksRentService
{
    /**
     * @var StudentRepository
     */
    private $studentRepository;

    /**
     * @var PrivateBookRepository
     */
    private $privateBookRepository;

    /**
     * @var StudentsBooksRentRepository
     */
    private $booksRentRepository;

    /**
     * StudentsBooksRentService constructor.
     * @param StudentRepository $studentRepository
     * @param PrivateBookRepository $privateBookRepository
     * @param StudentsBooksRentRepository $booksRentRepository
     */
    public function __construct(
        StudentRepository $studentRepository,
        PrivateBookRepository $privateBookRepository,
        StudentsBooksRentRepository $booksRentRepository)
    {
        $this->studentRepository = $studentRepository;
        $this->privateBookRepository = $privateBookRepository;
        $this->booksRentRepository = $booksRentRepository;
    }

    // 借书申请流程
    public function applyForBookRent(Student $student, $sharedBookId, $ownerId)
    {
        // 检查图书是否存在
        /** @var PrivateBook $privateBook */
        $privateBook = $this->privateBookRepository
            ->with(['sharedBook'])
            ->findWhere([
                'shared_book_id' => $sharedBookId,
                'student_id' => $ownerId,
                //  只有上架状态的图书才可以被借出.
                'is_in_shelf' => true
            ])->first();
        if (is_null($privateBook) || !$privateBook->sharedBook()->exists()) {
            throw new RentBookException('申请借阅的图书不存在');
        }

        // 书籍用户是否有效
        /** @var Student $lender */
        $lender = $this->studentRepository
            ->pushCriteria(new EnabledCriteria())
            ->findWhere(['id' => $ownerId])
            ->first();
        if (is_null($lender)) {
            throw new RentBookException('图书所有者不存在');
        }

        // 图书是否被借出
        $rentedOne = $this->booksRentRepository
            ->scopeQuery(function ($query) use ($sharedBookId, $ownerId) {
                return $query->where([
                    'shared_book_id' => $sharedBookId,
                    // 以书籍的所有者去找
                    'lender_id' => $ownerId,
                ]);
            })
            ->findWhereIn('statement', [
                Definition::SHARED_BOOK_RENT_STATE_RENTING,
                Definition::SHARED_BOOK_RENT_STATE_RETURNING
            ])
            ->first();
        if ($rentedOne) {
            throw new RentBookException('该图书已被借出');
        }

        // 重复请求
        $existedRequest = $this->booksRentRepository
            ->scopeQuery(function ($query) use ($privateBook, $student, $ownerId) {
                return $query->where([
                    'private_book_id' => $privateBook->getAttribute('id'),
                    'shared_book_id' => $privateBook->getAttribute('shared_book_id'),
                    'renter_id' => $student->getAttribute('id'),
                    'lender_id' => $ownerId,
                ]);
            })
            ->findWhereIn('statement', [
                Definition::SHARED_BOOK_RENT_STATE_APPLYING
            ])
            ->first();
        if ($existedRequest) {
            throw new RentBookException('重复借阅申请');
        }

        /** @var SharedBook $sharedBook */
        $sharedBook = $privateBook->sharedBook;
        $attributes = [
            'private_book_id' => $privateBook->getAttribute('id'),
            'renter_id' => $student->getAttribute('id'),
            'renter_name' => $student->getAttribute('name'),
            'lender_id' => $lender->getAttribute('id'),
            'lender_name' => $lender->getAttribute('name'),
            'shared_book_id' => $sharedBook->getAttribute('id'),
            'shared_book_name' => $sharedBook->getAttribute('name'),
            'shared_book_cover' => $sharedBook->getAttribute('cover'),
            'statement' => Definition::SHARED_BOOK_RENT_STATE_APPLYING,
            'rend_applied_at' => Carbon::now()
        ];

        $rent = $this->booksRentRepository->create($attributes);
        return $rent;
    }

    // 取消图书借阅申请
    public function cancelForBookRent(StudentsBooksRent $booksRent)
    {
        return $booksRent->cancelRentApplication();
    }

    // 同意借书流程
    public function grantForBookRent(StudentsBooksRent $booksRent)
    {
        return $booksRent->grantRentApplication();
    }

    // 申请归还图书
    public function applyForBookReturn(StudentsBooksRent $booksRent)
    {
        return $booksRent->commitReturnApplication();
    }

    // 确认图书归还
    public function grantForBookReturn(StudentsBooksRent $booksRent)
    {
        $result = $booksRent->grantReturnApplication();
        event(new SharedBookReturnedEvent($booksRent));
        return $result;
    }

    // 拒绝图书借阅
    public function rejectForBookRent(StudentsBooksRent $booksRent)
    {
        $res =  $booksRent->rejectRentApplication();
        // dump(['@service' => $res]);
        return $res;
    }

    // 用户借出列表
    public function rentListByStudent($studentId)
    {
        return $this->booksRentRepository
            ->scopeQuery(function ($query) use ($studentId) {
                return $query
                    ->select([
                        'id',
                        'renter_id',
                        'renter_name',
                        'lender_id',
                        'lender_name',
                        'shared_book_id',
                        'shared_book_name',
                        'shared_book_cover',
                        'statement'
                    ])
                    ->where([
                        'renter_id' => $studentId
                    ]);
            })
            ->paginate();
    }
}