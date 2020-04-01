<?php


namespace App\Repositories\mine;


use App\Models\baseinfo\BeanRecord;
use Prettus\Repository\Eloquent\BaseRepository;

class BeanRecordRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return BeanRecord::class;
    }
}