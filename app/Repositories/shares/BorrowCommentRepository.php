<?php


namespace App\Repositories\shares;


use App\Models\shares\BorrowComment;
use Prettus\Repository\Eloquent\BaseRepository;

class BorrowCommentRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return BorrowComment::class;
    }
}