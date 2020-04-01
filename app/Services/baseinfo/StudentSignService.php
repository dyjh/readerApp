<?php


namespace App\Services\baseinfo;


use App\Common\Definition;
use App\Exceptions\TeaException;
use App\Models\baseinfo\BeanRecord;
use App\Models\baseinfo\Student;
use App\Models\baseinfo\StudentSign;
use App\Models\config\Config;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StudentSignService
{
    /**
     * @var StudentSign
     */
    private $studentSign;

    /**
     * @var BeanRecord
     */
    private $beanRecord;

    /**
     * StudentSignService constructor.
     * @param StudentSign $studentSign
     * @param BeanRecord $beanRecord
     */
    public function __construct(StudentSign $studentSign, BeanRecord $beanRecord)
    {
        $this->studentSign = $studentSign;
        $this->beanRecord = $beanRecord;
    }

    // 用户签到
    public function signWithUser(Student $student)
    {
        DB::beginTransaction();
        try {
            /** @var StudentSign $result */
            $sign = StudentSign::sign($student->getAttribute('id'));
            if ($sign) {
                $continues = $sign->getAttribute('continue_days');

                $firstAmount = Config::get(
                    Definition::CONFIG_MODULE_SIGN,
                    'first_amount',
                    config('sign.first_amount')
                );
                $continueDay = Config::get(
                    Definition::CONFIG_MODULE_SIGN,
                    'continue_day',
                    config('sign.continue_day')
                );
                $increaseAmount = Config::get(
                    Definition::CONFIG_MODULE_SIGN,
                    'increase_amount',
                    config('sign.increase_amount')
                );

                // 积分增长
                if ($continues > $continueDay) {
                    $bean = $firstAmount * $continueDay;
                } else if ($continues > 1 && $continues <= $continueDay) {
                    $bean = $increaseAmount * $continues;
                } else {
                    $bean = $firstAmount;
                }

                $student->acquireBeanBy($bean, Definition::BOOK_BEAN_CHANGE_BY_SIGN);
                DB::commit();
                return [
                    'total_beans' => $student->getAttribute('total_beans'),
                    'earn' => $bean
                ];
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new TeaException('1001', $exception);
        }
        return false;
    }

    // 签到记录
    public function signLog(int $studentId)
    {
        $record = StudentSign
            ::where([
                'student_id' => $studentId,
                'month' => Carbon::now()->format('Ym')
            ])
            ->first();
        if ($record) {
            $record->signed = ($record->mask & (1 << Carbon::now()->day)) > 0;
        }
        return $record;
    }
}