<?php

namespace App\Http\Controllers\api\store;

use App\Events\Order\OrderCancel;
use App\Http\Filters\Store\OrderFilter;
use App\Http\Requests\store\CreateOrder;
use App\Http\Requests\store\RefundOrder;
use App\Http\Resources\Store\OrderCollection;
use App\Http\Resources\Store\OrderItemsCollection;
use App\Http\Resources\Store\OrderItemsResource;
use App\Http\Resources\Store\OrderResource;
use App\Http\Resources\Store\RefundReasonResource;
use App\Models\baseinfo\Student;
use App\Models\stores\Cart;
use App\Models\stores\Order;
use App\Models\stores\OrderItem;
use App\Models\stores\RefundReason;
use App\Repositories\store\OrderRepositoryRepositoryEloquent;
use App\Services\store\Contracts\OrderGeneratorInterface;
use App\Services\store\Exceptions\OrderGeneratorException;
use App\Services\store\OrderGenerator;
use App\Services\store\OrderHandlers\ItemHandler;
use App\Services\store\OrderHandlers\OrderHandler;
use App\Services\store\OrderHandlers\PostageHandler;
use App\Services\store\OrderHandlers\UserHandler;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Cache;

class OrderController extends StoreController
{

    private $repository;

    public function __construct(OrderRepositoryRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 生成订单
     * @param OrderGeneratorInterface|OrderGenerator $generator
     * @param CreateOrder $request
     * @return OrderResource
     * @throws \Exception
     * @throws \Throwable
     */
    public function store(OrderGeneratorInterface $generator, CreateOrder $request)
    {
        $itemsRequest = json_decode($request->get('items'),true);

        /**
         * @var Student $user
         */
        $user = $request->user();

        $selectItems = collect($itemsRequest)->keyBy('id');
        $items       = Cart::with(['product'])->whereKey($selectItems->keys())->get()->keyBy('id');
        throw_if(($items->count() ==0), OrderGeneratorException::class, 40013);

        $items->each(function (Cart $cart, int $key) use ($selectItems) {
            $cart->setAttribute('product_count', $selectItems[$key]['product_count']);
        });

        DB::beginTransaction();

        /**
         * @var Order $order
         */
        $order = $generator
            ->pushHandler(new ItemHandler($items, $user))
            ->pushHandler(new UserHandler($user))
            ->pushHandler(new OrderHandler())
            ->pushHandler(new PostageHandler($request->only(['province','city','district','address','contact_name','contact_number'])))
            ->generate();

        DB::commit();

        return new OrderResource($order);
    }

    /**
     * 订单列表
     */
    public function index(OrderFilter $filter)
    {
        $orders = Auth::user()->orders()
            ->with([
                'orderItems',
                'postage'
            ])
            ->latest()
            ->filter($filter)
            ->paginate();

        return OrderCollection::make($orders);
    }

    public function show(Order $order)
    {
        $order->load(['orderItems', 'postage']);

        return OrderResource::make($order);
    }


    /**
     * 确认收货
     */
    public function confirmOrder(Order $order)
    {
        throw_if(($order->statement !=3), OrderGeneratorException::class, 40004);

        $order->update(['statement' => 4]);

        return $this->json();
    }

    /**
     * 取消订单
     */
    public function cancelOrder(Order $order)
    {
        throw_if(($order->statement !=1), OrderGeneratorException::class,  40006);

        DB::beginTransaction();

        $order->update(['statement' => 8]);

        OrderCancel::dispatch($order);

        DB::commit();

        return $this->json();
    }

    /**
     * 提醒发货
     */
    public function remindOrder(Order $order)
    {
        throw_if(($order->statement !=2), OrderGeneratorException::class, 40005);
        throw_if(empty(!Cache::get('order_remind-'.$order->trade_no)), OrderGeneratorException::class, 40016);
        //短信提示

        Cache::put('order_remind-'.$order->trade_no, 1, Carbon::now()->addDays(1));
        return $this->json();
    }

    /**
     * 申请退款
     * @param Order $order
     */
    public function refundOrder(Order $order, RefundOrder $request)
    {
        throw_if((!in_array($order->statement,[2, 3, 4])), OrderGeneratorException::class, 40014);

        $this->repository->refundOrder($request->all());

        return $this->json();
    }

    /**
     * 退款商品列表
     */
    public function refundOrders()
    {
        $OrderItem = OrderItem::with('order')
            ->where('student_id', \Auth::id())
            ->whereIn('statement',[3, 4, 5])
            ->latest()
            ->paginate();

        return OrderItemsCollection::make($OrderItem);
    }

    /**
     * 退款商品详情
     * @param OrderItem $item
     */
    public function refundOrderDetail(OrderItem $item)
    {
        $item->load(['order', 'order.postage']);

        return OrderItemsResource::make($item);
    }

    /**
     * 退款原因类型列表
     */
    public function refundReasons()
    {
        return $this->json(RefundReasonResource::collection(RefundReason::status()->get()));
    }
}
