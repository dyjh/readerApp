<?php

namespace App\Imports;

use App\Models\School;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SchoolsImport implements ToModel , WithChunkReading, ShouldQueue
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (!isset($row[0])) {
            return null;
        }
        return new School([
            'province'    => $row[0],
            'city'        => $row[1],
            'district'    => $row[2],
            'name'        => $row[3],
            'address'     => $row[4],
            'school_type' => $row[5],
            'approach'    => $row[6],
            'special'   => $row[8],
        ]);
    }

    public function chunkSize(): int
    {
        return 10000;
    }

    public function batchSize(): int
    {
        return 130000;
    }

}
