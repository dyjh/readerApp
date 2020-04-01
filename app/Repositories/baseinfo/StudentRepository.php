<?php


namespace App\Repositories\baseinfo;


use App\Models\baseinfo\Student;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Validator\Contracts\ValidatorInterface;

class StudentRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Student::class;
    }

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'name' => 'required|min:1|max:20',
            'password' => 'required|min:6',
            'avatar' => 'required|max:191',
            'school_id' => 'required|exists:schools,id',
            'grade_id' => 'required|exists:grades,id',
            'ban_id' => 'required|exists:bans,id',
            'school_name' => 'required|max:50',
            'grade_name' => 'required|max:50',
            'ban_name' => 'required|max:50',
            'phone' => 'required|phone',
            'province' => 'required|max:20',
            'city' => 'required|max:20',
            'district' => 'required|max:20',
            'total_beans' => 'nullable',
            'read_count' => 'nullable',
            'share_count' => 'nullable'
        ],
        ValidatorInterface::RULE_UPDATE => [
            'phone' => 'nullable|phone|unique:students,phone',
            'name' => 'nullable|min:1|max:20',
            'school_id' => 'nullable|exists:schools,id',
            'grade_id' => 'nullable|exists:grades,id',
            'ban_id' => 'nullable|exists:bans,id',
            'school_name' => 'nullable|max:50',
            'grade_name' => 'nullable|max:50',
            'ban_name' => 'nullable|max:50',
            'city' => 'nullable|max:20',
            'district' => 'nullable|max:20',
        ]
    ];


}