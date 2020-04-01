<?php


namespace App\Services\platform;


use App\Http\Resources\platform\WhiteBoardResource;
use App\Models\mooc\LessonChapter;
use GuzzleHttp\Client;
use Illuminate\Http\Response;

/**
 * @property \Illuminate\Config\Repository|mixed token
 */
class WhiteBoardService
{
    /**
     * @var \Illuminate\Config\Repository
     */
    private $endpoint;

    /**
     * @var Client
     */
    private $postman;

    /**
     * WhiteBoardService constructor.
     */
    public function __construct()
    {
        $this->endpoint = config('whiteboard.endpoint');
        $this->token = config('whiteboard.token');

        $this->postman = new Client([
            'base_uri' => $this->endpoint,
            'timeout' => 2.0,
            'verify' => false,
            'headers' => [
                'Content-Type' => 'application/json'
            ],
        ]);
    }


    // 创建房间
    public function createRoom(string $lessonChapterId, string $name, int $limit, string $mode = 'persistent')
    {
        $lessonChapter = LessonChapter::with('board')->find($lessonChapterId);
        if (is_null($lessonChapter)) {
            return [
                'status' => false,
                'message' => '课程小节不存在'
            ];
        }
        if ($lessonChapter->board()->exists()) {
            return [
                'status' => true,
                'board' => new WhiteBoardResource($lessonChapter->board)
            ];
        }
        $api = "/room?token={$this->token}";
        // name  string	白板名称
        // limit number	白板最大人数；为0时，不限制人数
        // mode  string	v2版本参数；房间类型：transitory, persistent, historied
        try {
            $response = $this->postman->post($api, [
                'body' => json_encode([
                    'name' => $name,
                    'limit' => $limit,
                    'mode' => $mode
                ])
            ]);
        } catch (\Exception $exception) {
            return [
                'status' => false,
                'message' => '白板接口请求创建错误'
            ];
        }

        if ($response->getStatusCode() === Response::HTTP_OK) {
            $contentString = $response->getBody()->getContents();
            $content = json_decode($contentString, true);
            if ($content['code'] == 200) {
                $attributes = [
                    'token' => $content['msg']['roomToken'],
                    'uuid' => $content['msg']['room']['uuid'],
                    'name' => $content['msg']['room']['name'],
                    'limit' => $content['msg']['room']['limit'],
                    'mode' => $content['msg']['hare']['mode'],
                ];

                $board = $lessonChapter->board()->create($attributes);

                return [
                    'status' => true,
                    'board' => $board
                ];
            } else {
                // 接口参数错误
                return [
                    'status' => false,
                    'message' => $content['msg']
                ];
            }
        } else {
            // http exception
            return [
                'status' => false,
                'message' => '白板接口响应不正确'
            ];
        }
    }
}