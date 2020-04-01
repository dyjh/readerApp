<?php

namespace App\Listeners\Mooc;

use App\Events\Mooc\LessonCommented;
use App\Models\mooc\LessonComment;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;

class UpdateLessonRating implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  LessonCommented  $event
     * @return void
     */
    public function handle(LessonCommented $event)
    {
        $result = LessonComment::query()
            ->where('lesson_id', $event->comment->lesson_id)
            ->first([
                DB::raw('count(*) as comment_counts'),
                DB::raw('avg(rate) as rates')
            ]);
        // 更新课程的评分
        $event->comment->lesson->update([
            'rates'          => bcadd($result->rates,0,1),
        //      'comment_counts' => $result->comment_counts,
        ]);
    }
}
