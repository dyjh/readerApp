<?php

namespace App\Policies\shares;

use App\Models\baseinfo\Student;
use App\Models\shares\PrivateBook;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrivateBookPolicy
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

    // 是否是用户的图书
    public function access(Student $student, PrivateBook $privateBook)
    {
        return $student->getAttribute('id') == $privateBook->getAttribute('student_id');
    }
}
