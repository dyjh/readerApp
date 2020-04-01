<?php

namespace App\Http\Controllers\api\platform;

use App\Common\ResponseStatement;
use App\Common\traits\APIResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\platform\WhiteBoardCreationRequest;
use App\Services\platform\WhiteBoardService;

class WhiteBoardController extends Controller
{
    use APIResponseTrait;

    /**
     * @var WhiteBoardService
     */
    private $boardService;

    /**
     * WhiteBoardController constructor.
     * @param WhiteBoardService $boardService
     */
    public function __construct(WhiteBoardService $boardService)
    {
        $this->boardService = $boardService;
    }

    /**
     * 创建白板
     * @param WhiteBoardCreationRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author marhone
     */
    public function createRoom(WhiteBoardCreationRequest $request)
    {
        $lessonChapterId = $request->post('lesson_chapter_id');
        $name = $request->post('name');
        $limit = $request->post('limit');
        $mode = $request->post('mode');

        $response = $this->boardService->createRoom($lessonChapterId, $name, $limit, $mode);
        if (!$response['status']) {
            return $this->json($response['message'], ResponseStatement::STATUS_ERROR);
        }

        return $this->json($response['board']);
    }

}
