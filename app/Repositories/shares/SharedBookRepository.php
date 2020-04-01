<?php


namespace App\Repositories\shares;


use App\Models\shares\SharedBook;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Validator\Contracts\ValidatorInterface;

class SharedBookRepository extends BaseRepository
{
    // 模糊查询字段
    protected $fieldSearchable = [
        'name' => 'like',
        'author' => 'like'
    ];

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'owner_student_id' => 'required|exists:students,id',
            'school_id' => 'required|exists:schools,id',
            'grade_id' => 'required|exists:grades,id',
            'ban_id' => 'required|exists:bans,id',
            'name' => 'required|max:50',
            'author' => 'required|max:50',
            'publisher' => 'required|max:50',
            'isbn' => 'required|max:20',
            'cover' => 'required|max:191',
            'desc' => 'required|max:191',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'owner_student_id' => 'nullable|exists:students,id',
            'school_id' => 'nullable|exists:schools,id',
            'grade_id' => 'nullable|exists:grades,id',
            'ban_id' => 'nullable|exists:bans,id',
            'name' => 'nullable|max:50',
            'author' => 'nullable|max:50',
            'publisher' => 'nullable|max:50',
            'isbn' => 'nullable|max:20',
            'cover' => 'nullable|max:191',
            'desc' => 'nullable|max:191',
        ]
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SharedBook::class;
    }

    public function boot()
    {
        // 路由上的查询标准
        $this->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
    }
}