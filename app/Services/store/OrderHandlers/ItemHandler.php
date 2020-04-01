<?php

namespace App\Services\store\OrderHandlers;

use App\Models\baseinfo\Student;
use App\Models\stores\Cart;
use App\Models\stores\Contracts\OrderInterface;
use App\Models\stores\Order;
use App\Models\stores\OrderItem;
use App\Models\stores\ProductBook;
use App\Services\store\Contracts\AbstractOrderHandler;
use App\Services\store\Contracts\OrderGeneratorInterface;
use App\Services\store\Exceptions\OrderGeneratorException;
use Illuminate\Support\Collection;

class ItemHandler extends AbstractOrderHandler
{
    /**
     * @var Collection
     */
    protected $productItems;

    /**
     * @var Collection
     */
    protected $orderItems;

    /**
     * @var Student
     */
    protected $user;

    /**
     * ItemHandler constructor.
     * @param Collection $productItems
     */
    public function __construct(Collection $productItems, Student $user)
    {
        $this->productItems = $productItems;

        $this->orderItems = collect();

        $this->user = $user;
    }

    /**
     * @param OrderInterface|Order $order
     * @param OrderGeneratorInterface $orderGenerator
     */
    public function beforeHandle(OrderInterface $order, OrderGeneratorInterface $orderGenerator)
    {
        $this->productItems->each(function (Cart $productItem) use ($order) {
            $product_count = $productItem->getAttribute('product_count', 1);

            throw_if(($product_count > $productItem->product->getAttribute('stock')), OrderGeneratorException::class, 40001, $productItem->product->getAttribute('name').' 商品库存不足！');
            throw_if(($productItem->product->getAttribute('on_sale')!=1), OrderGeneratorException::class, 40002, $productItem->product->getAttribute('name').' 商品已下架!');

            $orderItem = new OrderItem([
                'product_name'      => $productItem->product->name,
                'product_price'     => $productItem->product->sell_price,
                'product_cover'     => $productItem->product->cover,
                'product_count'     => $product_count,
                'total'             => bcmul($productItem->product->sell_price, $product_count, 2),
                'statement'         => 0,
            ]);
            $orderItem->user()->associate($this->user);
            $orderItem->product()->associate($productItem->product);
            $this->orderItems->push($orderItem);
            $order->setAttribute('total', bcadd($order->total, $orderItem->total, 2));
            $order->setAttribute('tag_price', bcadd($order->tag_price, bcmul($productItem->product->tag_price, $product_count, 2), 2));
            $productItem->product->decrement('stock', $product_count);
        });
        $order->setAttribute('total_amount', $this->orderItems->sum('product_count'));
        $order->setAttribute('title', '购买' . $this->orderItems->first()->product_name . ($this->orderItems->count() > 1 ? '等商品' : ''));
    }

    /**
     * @param OrderInterface|Order $order
     * @param OrderGeneratorInterface $orderGenerator
     */
    public function afterHandle(OrderInterface $order, OrderGeneratorInterface $orderGenerator)
    {
        $order->setRelation('orderItems', $order->orderItems()->saveMany($this->orderItems));

        Cart::whereIn('id', $this->productItems->pluck('id'))->delete();
    }

    /**
     * @return Collection
     */
    public function getProductItems()
    {
        return $this->productItems;
    }
}
