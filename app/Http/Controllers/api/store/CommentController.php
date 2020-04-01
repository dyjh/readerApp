<?php

namespace App\Http\Controllers\api\store;

use App\Events\Order\OrderItemCommented;
use App\Http\Requests\store\CreateProductComment;
use App\Http\Resources\Store\ProductCommentCollection;
use App\Http\Resources\Store\ProductCommentResource;
use App\Models\stores\OrderItem;
use App\Models\stores\ProductBook;
use App\Models\stores\ProductBookComment;
use App\Services\store\Exceptions\OrderGeneratorException;
use DB;

class CommentController extends StoreController
{
    public function index(ProductBook $book)
    {
        return ProductCommentCollection::make($book->comments()->with(['user'])->latest()->paginate());
    }

    /**
     * @param OrderItem $orderItem
     * @param CreateProductComment $request
     * @return ProductCommentResource
     * @throws \Throwable
     */
    public function store(OrderItem $orderItem, CreateProductComment $request)
    {
        throw_if(($orderItem->statement !=1), OrderGeneratorException::class, 40003);

        $order = $orderItem->order;

        throw_if(!in_array($order->statement, [4,9]), OrderGeneratorException::class, 40005);

        /**
         * @var ProductBook $product
         */
        $product = $orderItem->product;

        DB::beginTransaction();

        /**
         * @var ProductBookComment $comment
         */
        $comment = $product->comments()->create([
            'student_id'       => $order->student_id,
            'product_book_id'  => $orderItem->product_id,
            'order_id' => $order->id,
            'content'  => $request->get('content', ''),
            'service_attitude_rate'  => $request->get('service_attitude_rate', ''),
            'desc_match_rate'  => $request->get('desc_match_rate', ''),
        ]);

        $orderItem->update(['statement' => 2]);

        OrderItemCommented::dispatch($comment);

        DB::commit();

        return $this->json();
    }
}
