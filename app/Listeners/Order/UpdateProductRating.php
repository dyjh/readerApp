<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderItemCommented;
use App\Models\stores\ProductBookComment;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;

class UpdateProductRating implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  OrderItemCommented  $event
     * @return void
     */
    public function handle(OrderItemCommented $event)
    {
        $result = ProductBookComment::query()
            ->where('product_book_id', $event->comment->product_book_id)
            ->whereHas('order', function ($query) {
                $query->whereNotNull('paid_at');
            })
            ->first([
                DB::raw('count(*) as comment_counts'),
                DB::raw('avg(desc_match_rate) as rates')
            ]);
        // 更新商品的评分和评价数
        $event->comment->product->update([
            'rates'          => bcadd($result->rates,0,1),
            'comment_counts' => $result->comment_counts,
        ]);
    }
}
