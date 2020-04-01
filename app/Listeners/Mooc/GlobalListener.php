<?php

namespace App\Listeners\Mooc;

use App\Models\mooc\LessonChapter;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Log;
use Liebig\Cron\Cron;
use RongCloud\RongCloud;

class GlobalListener
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen('cron.collectJobs', function () {
            //TODO 自定义定时任务
            $day = date('Y-m-d');
            $time = date('H:i:s');
            $time_start = date('H:i:s', time() - 600);
            $time_end = date('H:i:s', time() + 600);
            //监听直播开始
            Cron::add('checkMoocStart', '*/3 * * * *', function () use ($day, $time_start, $time) {
                $chapters = LessonChapter::where([
                    ['broadcast_day', $day],
                    ['is_streamed', LessonChapter::LIVE],
                    ['chat_room', '{}']
                ])->whereBetween('broadcast_start_at', [$time_start, $time])
                    ->get();
                foreach ($chapters as $chapter) {
                    $RongSDK = new RongCloud(config('latrell-rcloud.app_key'), config('latrell-rcloud.app_secret'));
                    $chatRoom = [
                        'id' => $chapter->id,
                        'name' => $chapter->name . '-' . $chapter->id
                    ];
                    $result = $RongSDK->getChatroom()->create([$chatRoom]);
                    if ($result['code'] == 200) {
                        $chapter->chat_room = json_encode($chatRoom);
                        $chapter->save();
                    }
                }
            });

            //监听直播结束
            Cron::add('checkMoocStop', '*/3 * * * *', function () use ($day, $time_end, $time) {
                $chapters = LessonChapter::where([
                    ['broadcast_day', $day],
                    ['is_streamed', LessonChapter::PLAYBACK],
                    ['broadcast_ent_at', '<=', $time_end]
                ])->get();
                foreach ($chapters as $chapter) {
                    $RongSDK = new RongCloud(config('latrell-rcloud'));
                    $chatRoom = [
                        'id' => $chapter->id,
                    ];
                    $result = $RongSDK->getChatroom()->destory($chatRoom);
                    Log::info('注销聊天室:' . $chapter->name . '-' . $chapter->id);
                }
            });
        });
    }
}