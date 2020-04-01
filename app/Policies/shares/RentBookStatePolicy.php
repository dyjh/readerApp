<?php

namespace App\Policies\shares;

use App\Models\baseinfo\Student;
use App\Models\shares\StudentsBooksRent;
use Illuminate\Auth\Access\HandlesAuthorization;

class RentBookStatePolicy
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

    // 图书持有者
    public function updateAsLender(Student $student, StudentsBooksRent $booksRent)
    {
        return $student->getAttribute('id') == $booksRent->getAttribute('lender_id');
    }

    // 图书借阅者
    public function updateAsRenter(Student $student, StudentsBooksRent $booksRent)
    {
        return $student->getAttribute('id') == $booksRent->getAttribute('renter_id');
    }

    public function accessAsLender(Student $student, StudentsBooksRent $booksRent)
    {
        return $student->getAttribute('id') == $booksRent->getAttribute('lender_id');
    }

    public function accessAsRenter(Student $student, StudentsBooksRent $booksRent)
    {
        return $student->getAttribute('id') == $booksRent->getAttribute('renter_id');
    }

}
