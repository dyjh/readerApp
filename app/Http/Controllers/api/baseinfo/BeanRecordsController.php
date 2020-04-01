<?php

namespace App\Http\Controllers\api\baseinfo;

use App\Http\Resources\baseinfo\BeanRecordCollection;
use App\Services\baseinfo\BeanRecordsService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BeanRecordsController extends Controller
{
    /**
     * @var BeanRecordsService
     */
    private $recordsService;

    /**
     * BeanRecordsController constructor.
     * @param BeanRecordsService $recordsService
     */
    public function __construct(BeanRecordsService $recordsService)
    {
        $this->recordsService = $recordsService;
    }

    public function history(Request $request)
    {
        $query = $request->get('change_bys', '');
        $changeBys = array_map('trim', explode(',', $query));
        $changeBys = array_filter($changeBys, function ($value) {
            if (!empty($value)) return $value;
        });

        $studentId = auth('api')->id();
        $history = $this->recordsService->history($studentId, $changeBys);

        return new BeanRecordCollection($history);
    }
}
