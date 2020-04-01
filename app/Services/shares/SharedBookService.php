<?php


namespace App\Services\shares;


use App\Criteria\common\EnabledCriteria;
use App\Models\baseinfo\Student;
use App\Repositories\baseinfo\StudentRepository;
use App\Repositories\shares\PrivateBookRepository;
use App\Repositories\shares\SharedBookRepository;

class SharedBookService
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
     * @var StudentRepository
     */
    private $studentRepository;

    /**
     * SharedBookService constructor.
     * @param SharedBookRepository $sharedBookRepository
     * @param PrivateBookRepository $privateBookRepository
     * @param StudentRepository $studentRepository
     */
    public function __construct(
        SharedBookRepository $sharedBookRepository,
        PrivateBookRepository $privateBookRepository,
        StudentRepository $studentRepository)
    {
        $this->sharedBookRepository = $sharedBookRepository;
        $this->privateBookRepository = $privateBookRepository;
        $this->studentRepository = $studentRepository;
    }

    /**
     * 根据区域(学校, 班级) 查找图书列表
     * @param Student $student
     * @param string $area
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @author marhone
     */
    public function findBookByArea(Student $student, string $area = 'school')
    {
        $criteria = [
            'school_id' => $student->getAttribute('school_id')
        ];
        if ($area == 'ban') {
            $criteria['grade_id'] = $student->getAttribute('grade_id');
            $criteria['ban_id'] = $student->getAttribute('ban_id');
        }

        $books = $this->sharedBookRepository
            ->scopeQuery(function ($query) use ($criteria) {
                return $query
                    ->select([
                        'id',
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
                    ])
                    ->where($criteria);
            })
            ->pushCriteria(new EnabledCriteria())
            ->paginate();
        return $books;
    }

    /**
     * 根据区域(学校, 班级) 查找图书的持有者
     * @param $sharedBookId
     * @param string $area
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @author marhone
     */
    public function findBookOwnersByArea($sharedBookId, string $area = 'school')
    {
        $sharedBook = $this->sharedBookRepository->find($sharedBookId);
        $criteria = [
            'school_id' => $sharedBook->getAttribute('school_id'),
        ];
        if ($area == 'ban') {
            // 班级内图书所有者
            $criteria['grade_id'] = $sharedBook->getAttribute('grade_id');
            $criteria['ban_id'] = $sharedBook->getAttribute('ban_id');
        }

        // show private books
        $privateBooks = $this->privateBookRepository
            ->scopeQuery(function ($query) use ($criteria, $sharedBookId) {
                return $query
                    ->with(['owner' => function ($query) {
                        $query->select(['id', 'name', 'avatar', 'school_id', 'grade_id', 'ban_id']);
                    }])
                    ->with(['lending'])
                    ->whereHas('owner', function ($query) use ($criteria) {
                        return $query
                            // note: 用户状态(禁用/启用).
                            ->where('status', 1)
                            ->where($criteria);
                    })
                    ->select(['id', 'shared_book_id', 'student_id', 'rent_count', 'is_in_shelf'])
                    ->where([
                        'shared_book_id' => $sharedBookId,
                        'is_in_shelf' => true
                    ]);
            })
            ->all();

        return $privateBooks;
    }

    public function findBookByID($id)
    {
        return $this->sharedBookRepository
            ->pushCriteria(new EnabledCriteria())
            ->find($id);
    }
}