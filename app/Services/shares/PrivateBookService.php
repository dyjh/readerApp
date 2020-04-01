<?php


namespace App\Services\shares;


use App\Criteria\common\EnabledCriteria;
use App\Models\baseinfo\Student;
use App\Models\shares\PrivateBook;
use App\Models\shares\SharedBook;
use App\Repositories\shares\PrivateBookRepository;
use App\Repositories\shares\SharedBookRepository;
use function foo\func;

class PrivateBookService
{
    /**
     * @var SharedBookRepository
     */
    private $sharedBookRepository;

    /**
     * @var PrivateBookRepository
     */
    private $privateBookRepository;

    /**
     * PrivateBookService constructor.
     * @param SharedBookRepository $sharedBookRepository
     * @param PrivateBookRepository $privateBookRepository
     */
    public function __construct(SharedBookRepository $sharedBookRepository, PrivateBookRepository $privateBookRepository)
    {
        $this->sharedBookRepository = $sharedBookRepository;
        $this->privateBookRepository = $privateBookRepository;
    }

    // 上传图书到共享书籍
    public function uploadBookToShare(Student $student, $isbn)
    {
        $schoolId = $student->getAttribute('school_id');
        $gradeId = $student->getAttribute('grade_id');
        $banId = $student->getAttribute('ban_id');

        /** @var SharedBook $sharedBook */
        $sharedBook = $this->sharedBookRepository
            ->scopeQuery(function ($query) use ($schoolId, $gradeId, $banId, $isbn) {
                return $query->where([
                    'school_id' => $schoolId,
                    'grade_id' => $gradeId,
                    'ban_id' => $banId,
                    'isbn' => $isbn
                ]);
            })
            ->first();

        if ($sharedBook) {
            $privateBook = $this->privateBookRepository->updateOrCreate([
                'shared_book_id' => $sharedBook->getAttribute('id'),
                'student_id' => $student->getAttribute('id')
            ]);
        } else {
            dd('todo: 调用ISBN查询API, 获取图书信息并关联');
        }
        // 增加用户书籍上传量
        $student->increment('share_count');

        return $privateBook;
    }

    // 获取用户上传的图书列表
    public function listBookByStudent($ownerID, $shelfStatus = null)
    {
        $privateBooks = $this->privateBookRepository
            ->scopeQuery(function ($query) use ($shelfStatus, $ownerID) {
                if (!is_null($shelfStatus)) {
                    if ($shelfStatus == 1) {
                        $query = $query->where('is_in_shelf', 1);
                    } else if ($shelfStatus == 0) {
                        $query = $query->where('is_in_shelf', 0);
                    }
                }
                return $query
                    ->select([
                        'id',
                        'shared_book_id',
                        'student_id',
                        'rent_count',
                        'is_in_shelf'
                    ])
                    ->with(['sharedBook' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'author',
                            'publisher',
                            'isbn',
                            'cover',
                            'desc',
                            'rent_counts',
                            'status'
                        ]);
                    }])
                    ->where([
                        'student_id' => $ownerID
                    ]);
            })
            ->paginate();

        return $privateBooks;
    }

    // 获取上传的书籍详情
    public function showSharedBook(PrivateBook $privateBook)
    {
        $privateBookId = $privateBook->getAttribute('id');
        $sharedBookId = $privateBook->getAttribute('shared_book_id');
        return $this->privateBookRepository
            ->scopeQuery(function ($query) use ($privateBookId, $sharedBookId) {
                return $query
                    ->with(['sharedBook' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'author',
                            'publisher',
                            'isbn',
                            'cover',
                            'desc',
                            'rent_counts',
                            'status'
                        ]);
                    }])
                    ->where([
                        'id' => $privateBookId,
                        'shared_book_id' => $sharedBookId
                    ]);
            })
            ->first();
    }

    // 书籍上架
    public function bookPutaway(PrivateBook $privateBook)
    {
        return $privateBook->putaway();
    }

    // 书籍下架
    public function bookRecycle(PrivateBook $privateBook)
    {
        return $privateBook->recycle();
    }
}