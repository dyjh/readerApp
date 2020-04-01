<?php


namespace App\Services\mooc;


use App\Models\mooc\LessonChapter;

class LessonsService
{
    public function saveLives():void
    {
        $date_day = date('Y-m-d');
        $data_time_end = date('H:i:s', time() - 600);
        $Qiniu = new QiniuStreamService();
        $chapters = LessonChapter::where([
            ['is_streamed', LessonChapter::LIVE],
            ['play_back_url', null],
            ['broadcast_day', $date_day],
            ['broadcast_ent_at', '<', $data_time_end]
        ])->select(['id', 'stream_key'])->get();
        foreach ($chapters as $chapter) {
            $fileName = $Qiniu->saveStream($chapter->stream_key, 0, 0);
            if ($fileName) {
                $chapter->play_back_url = $fileName;
                $chapter->is_streamed = LessonChapter::PLAYBACK;
                $chapter->save();
            }
        }
    }
}