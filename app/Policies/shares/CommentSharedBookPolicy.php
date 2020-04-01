<?php

namespace App\Policies\shares;

use App\Common\Definition;
use App\Models\baseinfo\Student;
use App\Models\shares\SharedBook;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentSharedBookPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // 是否可以评论图书
    public function commentAsRenter(Student $student, SharedBook $sharedBook)
    {
        // 完成借阅后才能评价图书
        $renterId = $student->getAttribute('id');
        $isRead = $sharedBook
            ->privateBooks()
            ->whereHas('rented', function ($query) use ($renterId) {
                $query->where([
                    'renter_id' => $renterId
                ]);
            })
            ->exists();

        return $isRead;
    }
}
