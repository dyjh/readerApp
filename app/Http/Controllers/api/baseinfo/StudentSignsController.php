<?php

namespace App\Http\Controllers\api\baseinfo;

use App\Common\ResponseStatement;
use App\Common\traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\baseinfo\StudentSignLogResource;
use App\Services\baseinfo\StudentSignService;

class StudentSignsController extends Controller
{
    use APIResponseTrait;

    /**
     * @var StudentSignService
     */
    private $signService;

    /**
     * StudentSignsController constructor.
     * @param StudentSignService $signService
     */
    public function __construct(StudentSignService $signService)
    {
        $this->signService = $signService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\TeaException
     * @author marhone
     */
    public function sign()
    {
        $student = auth('api')->user();

        $result = $this->signService->signWithUser($student);
        if (is_array($result)) {
            return $this->json($result);
        }

        return $this->json('重复签到', ResponseStatement::STATUS_ERROR);
    }

    /**
     * 签到记录
     * @return StudentSignLogResource
     * @author marhone
     */
    public function signLog()
    {
        $studentId = auth('api')->id();
        $log = $this->signService->signLog($studentId);

        return $this->json(new StudentSignLogResource($log));
    }
}
