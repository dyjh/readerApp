<?php


namespace App\Services\shares;


use App\Criteria\common\EnabledCriteria;
use App\Models\baseinfo\Student;
use App\Models\shares\SharedBook;
use App\Repositories\shares\BorrowCommentRepository;

class BorrowCommentService
{
    /**
     * @var BorrowCommentRepository
     */
    private $commentRepository;

    /**
     * BorrowCommentService constructor.
     * @param BorrowCommentRepository $commentRepository
     */
    public function __construct(BorrowCommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function commentByBook($bookId)
    {
        return $this->commentRepository
            ->scopeQuery(function ($query) use ($bookId) {
                return $query
                    ->where([
                        'shared_book_id' => $bookId
                    ])
                    ->latest();
            })
            ->pushCriteria(new EnabledCriteria())
            ->paginate();
    }

    // 发表借书读后评论
    public function publishBookReview(Student $student, SharedBook $sharedBook, $content)
    {
        $comment = $this->commentRepository->updateOrCreate([
            'shared_book_id' => $sharedBook->getAttribute('id'),
            'shared_book_name' => $sharedBook->getAttribute('name'),
            'student_id' => $student->getAttribute('id'),
            'student_name' => $student->getAttribute('name'),
            'student_avatar' => $student->getAttribute('avatar'),
            'content' => $content,
        ]);

        return $comment;
    }


}