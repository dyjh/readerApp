<?php

namespace App\Models\mooc;

use App\Models\abstracts\AbstractPayable;
use App\Models\HasOwner;
use App\Models\mooc\traits\LessonedTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentLessons extends AbstractPayable
{
    use SoftDeletes, LessonedTrait, HasOwner;

    protected $fillable = [
        'student_id',
        'lesson_id',
        'payment_statement',
        'paid_price',
        'payment_method',
        'trade_no',
        'trans_no'
    ];

    public const ALIPAY = 1;
    public const WXPAY = 2;
    public const FREE = 3;

    protected $table = 'student_lessons';

    public function getTotal(): float
    {
        return $this->getAttribute('paid_price');
    }

    public function getTitle(): string
    {
        return $this->lesson->getAttribute('name');
    }

    public function getStudentId(): int
    {
        return $this->getAttribute('student_id');
    }

    public function getTradeNo(): string
    {
        return $this->getAttribute('trade_no');
    }

    /**
     * @return Carbon
     */
    public function getExpiredAt(): Carbon
    {
        return Carbon::now()->addMinutes($this->getExpiredMinutes());
    }

    /**
     * @return integer
     */
    public function getExpiredMinutes(): int
    {
        return 30;
    }
}
