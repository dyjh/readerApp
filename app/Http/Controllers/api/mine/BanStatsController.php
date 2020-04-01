<?php

namespace App\Http\Controllers\api\mine;

use App\Common\traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\mine\BanSateUploadCollection;
use App\Services\mine\BanStatService;

class BanStatsController extends Controller
{
    use APIResponseTrait;

    /**
     * @var BanStatService
     */
    private $statService;

    /**
     * BanStatsController constructor.
     * @param BanStatService $statService
     */
    public function __construct(BanStatService $statService)
    {
        $this->statService = $statService;
    }

    // 班级阅读
    public function bookReadRankList()
    {
        $student = auth('api')->user();
        $stats = $this->statService->getReadRankByStudent($student);

        return $this->json($stats);
    }

    // 班级书籍上传排名
    public function bookUploadedRankList()
    {
        $student = auth('api')->user();
        $stats = $this->statService->getUploadRankByStudent($student);

        return $this->json($stats);
    }

    // 班级整体数据
    public function brief()
    {
        $student = auth('api')->user();
        $stats = $this->statService->getBanBriefByStudent($student);

        return $this->json($stats);
    }
}
