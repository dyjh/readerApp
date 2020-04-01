<?php


namespace App\Http\Controllers\api\mooc;


use App\Common\traits\APIResponseTrait;
use App\Models\mooc\LessonChapter;
use Illuminate\Http\Request;
use RongCloud\RongCloud;

class LiveController
{
    use APIResponseTrait;

    public function notify(Request $request)
    {
        $data = $request->input();
        $RongSDK = new RongCloud(config('latrell-rcloud.app_key'), config('latrell-rcloud.app_secret'));
        //待实现逻辑：根据七牛开播断播回调，修改直播状态，创建或者销毁聊天室
        $chapter = LessonChapter::where([
            ['is_streamed', LessonChapter::LIVE],
            ['chat_room', '{}']
        ])->first();
        switch ($data['status']) {
            case 'connected':
                $data['status'] = LessonChapter::IS_LIVING;
                $chatRoom = [
                    'id' => $chapter->id,
                    'name' => $chapter->name . '-' . $chapter->id
                ];
                $result = $RongSDK->getChatroom()->create([$chatRoom]);
                if ($result['code'] == 200) {
                    $RongSDK->getChatroom()->Keepalive()->add(['id' => $chapter->id]);
                    $chapter->chat_room = json_encode($chatRoom);
                    $chapter->save();
                }
                break;
            case 'close':
                $data['status'] = LessonChapter::CLOSE_LIVE;
                $chatRoom = [
                    'id' => $chapter->id,
                ];
                $RongSDK->getChatroom()->destory($chatRoom);
                break;
        }
        return true;
    }
}