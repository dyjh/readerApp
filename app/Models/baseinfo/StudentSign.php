<?php

namespace App\Models\baseinfo;

use App\Models\BaseModel;
use Illuminate\Support\Carbon;

class StudentSign extends BaseModel
{
    protected $table = 'student_signs';

    protected $fillable = [
        'student_id',
        'month',
        'mask',
        'continue_days'
    ];

    public static function sign($studentId)
    {
        $today = Carbon::now();
        $dayOfMonth = $today->day;
        $month = $today->format('Ym');
        $mask = 1 << $dayOfMonth;

        /** @var StudentSign $record */
        $record = self::where([
            'student_id' => $studentId,
            'month' => $month
        ])->first();

        if ($record) {
            // 非首次签到
            $oldMask = $record->getAttribute('mask');
            // 重复签到
            if ($oldMask & $mask) {
                return false;
            }
            // 本月连续签到
            if ($oldMask & (1 << ($dayOfMonth - 1))) {
                $continueDay = $record->getAttribute('continue_days') + 1;
            } else {
                $continueDay = 1;
            }
            $record->mask = $oldMask | $mask;
            $record->continue_days = $continueDay;
            $record->save();
        } else {
            // 本月首次签到
            // 检查上月签到
            $last = $today->subMonth();
            $lastMonth = $last->format('Ym');
            /** @var StudentSign $lastMonthRecord */
            $lastMonthRecord = self::where([
                'student_id' => $studentId,
                'month' => $lastMonth
            ])->first();
            $continueDay = 1;
            if ($lastMonthRecord && $dayOfMonth == 1) {
                $lastMonthMask = $lastMonthRecord->getAttribute('mask');
                if ($lastMonthMask & (1 << $last->daysInMonth)) {
                    $continueDay = $lastMonthRecord->getAttribute('continue_days') + 1;
                }
            }
            $record = self::create([
                'student_id' => $studentId,
                'month' => $month,
                'mask' => $mask,
                'continue_days' => $continueDay
            ]);
        }

        return $record;
    }
}
