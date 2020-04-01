<?php


namespace App\Services\mine;


use App\Models\baseinfo\Student;
use App\Repositories\baseinfo\StudentRepository;
use App\Repositories\shares\PrivateBookRepository;
use App\Repositories\shares\SharedBookRepository;

class BanStatService
{
    /**
     * @var StudentRepository
     */
    private $studentRepository;

    /**
     * @var SharedBookRepository
     */
    private $sharedBookRepository;

    /**
     * @var PrivateBookRepository
     */
    private $privateBookRepository;

    /**
     * BanStatService constructor.
     * @param StudentRepository $studentRepository
     * @param SharedBookRepository $sharedBookRepository
     * @param PrivateBookRepository $privateBookRepository
     */
    public function __construct(
        StudentRepository $studentRepository,
        SharedBookRepository $sharedBookRepository,
        PrivateBookRepository $privateBookRepository)
    {
        $this->studentRepository = $studentRepository;
        $this->sharedBookRepository = $sharedBookRepository;
        $this->privateBookRepository = $privateBookRepository;
    }

    // 班级内同学上传数量
    public function getUploadRankByStudent(Student $student)
    {
        $schoolId = $student->getAttribute('school_id');
        $gradeId = $student->getAttribute('grade_id');
        $banId = $student->getAttribute('ban_id');

        // get classmates
        $classmates = $this->studentRepository
            ->scopeQuery(function ($query) use ($schoolId, $gradeId, $banId) {
                return $query
                    ->select([
                        'name',
                        'avatar',
                        'share_count'
                    ])
                    ->where([
                        'school_id' => $schoolId,
                        'grade_id' => $gradeId,
                        'ban_id' => $banId
                    ])
                    ->orderBy('share_count', 'desc')
                    // 班级前十
                    ->limit(10);
            })
            ->get();
        $me = [
            'name' => $student->getAttribute('name'),
            'avatar' => $student->getAttribute('avatar'),
            'share_count' => $student->getAttribute('share_count')
        ];

        return [
            'ranks' => $classmates,
            'me' => $me
        ];
    }

    public function getReadRankByStudent(Student $student)
    {
        $schoolId = $student->getAttribute('school_id');
        $gradeId = $student->getAttribute('grade_id');
        $banId = $student->getAttribute('ban_id');

        // get classmates
        $classmates = $this->studentRepository
            ->scopeQuery(function ($query) use ($schoolId, $gradeId, $banId) {
                return $query
                    ->select([
                        'name',
                        'avatar',
                        'read_count'
                    ])
                    ->where([
                        'school_id' => $schoolId,
                        'grade_id' => $gradeId,
                        'ban_id' => $banId
                    ])
                    ->orderBy('read_count', 'desc')
                    // 班级前十
                    ->limit(10);
            })
            ->get();
        $me = [
            'name' => $student->getAttribute('name'),
            'avatar' => $student->getAttribute('avatar'),
            'read_count' => $student->getAttribute('read_count')
        ];

        return [
            'ranks' => $classmates,
            'me' => $me
        ];
    }

    public function getBanBriefByStudent(Student $student)
    {
        $schoolId = $student->getAttribute('school_id');
        $gradeId = $student->getAttribute('grade_id');
        $banId = $student->getAttribute('ban_id');

        $classmates = $this->studentRepository
            ->scopeQuery(function ($query) use ($schoolId, $gradeId, $banId) {
                return $query
                    ->select([
                        'share_count',
                        'read_count'
                    ])
                    ->where([
                        'school_id' => $schoolId,
                        'grade_id' => $gradeId,
                        'ban_id' => $banId
                    ]);
            })
            ->get();

        $info = [
            'students' => $classmates->count(),
            'reads' => 0,
            'shares' => 0
        ];

        foreach ($classmates as $classmate) {
            $info['reads'] += $classmate->getAttribute('read_count');
            $info['shares'] += $classmate->getAttribute('share_count');
        }

        return $info;
    }
}