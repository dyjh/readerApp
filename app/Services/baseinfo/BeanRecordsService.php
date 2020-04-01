<?php


namespace App\Services\baseinfo;


use App\Models\baseinfo\BeanRecord;

class BeanRecordsService
{
    /**
     * @var BeanRecord
     */
    private $beanRecord;

    /**
     * BeanRecordsService constructor.
     * @param BeanRecord $beanRecord
     */
    public function __construct(BeanRecord $beanRecord)
    {
        $this->beanRecord = $beanRecord;
    }

    public function history($studentId, $changeBys = [])
    {
        $beanRecord = new BeanRecord();
        $beanRecord = $beanRecord
            ->whereNotNull('changed_at')
            ->where([
            'student_id' => $studentId
        ]);

        if (!empty($changeBys)) {
            $beanRecord = $beanRecord->whereIn('changed_by', $changeBys);
        }

        $list = $beanRecord
            ->orderBy('created_at', 'desc')
            ->paginate();

        return $list;
    }
}