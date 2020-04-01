<?php

namespace App\Admin\Controllers\stores;

use App\Admin\Common\Presuppose;
use App\Admin\Controllers\stores\action\CheckOrder;
use App\Admin\Controllers\stores\action\DeliverOrder;
use App\Admin\Controllers\stores\tools\ShowArtwork;
use App\Admin\Extensions\widgets\ModalForm;
use App\Models\stores\Order;
use App\Models\stores\OrderItem;
use Doctrine\DBAL\Schema\Table;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class OrdersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '订单管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order);
        $grid->model()->orderBy('id', 'desc');
        $grid->column('id', __('Id'))->sortable();
        $grid->column('student_id', __('Student name'))->display(function (){return $this->user->name ?? '';});
        $grid->column('title', __('Order title'));
        $grid->column('trade_no', __('Trade no'))->filter('like');
//        $grid->column('tag_price', __('Tag price'));
        $grid->column('total', __('Total'));
        $grid->column('statement', __('Order statement'))
            ->using(trans("order.statuses"))
            ->sortable()
            ->help('订单状态')->label();
        $grid->column('ids', __('Refund Status'))->display(function (){
            $count = $this->orderItems->whereNotIn('refund_method', [0])->count();
            if($count)
            {
                return '有退款商品';
            }else{
                return '无';
            }
        })->label();
        $grid->column('total_amount', __('Total amount'));
        $grid->column('paid_at', __('Paid at'));
        $grid->column('payment_method', __('Payment method'))
            ->using(trans("order.pay_type"))
            ->sortable()
            ->help('支付类型')->filter(trans("order.pay_type"))->label();
//        $grid->column('refund_total', __('Refund total'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('发货操作')->pop(function () {
            $form = new \Encore\Admin\Widgets\Form();
            $form->action('deliverOrder/'.$this->row->id);
            $form->text('express_number', '快递单号')->help('快递单号');
            $form->hidden('id',$this->row->id);
            $status = ($this->row->statement == 2)?true : false;

            $count = $this->row->orderItems->whereNotIn('refund_method', [0])->count();

            if($count >= $this->row->orderItems->count())
            {
                $status = false;
            }
            return new ModalForm(['name'=>'发货','enabled'=> $status], '订单发货', $form);
        });
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
        });

        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $filter->expand();
            $filter->column(1/2, function ($filter) {
                $filter->like('trade_no','订单号');
                $filter->equal('statement','订单状态')->select(trans("order.statuses"));
            });
            $filter->column(1/2, function ($filter) {
                $filter->between('created_at','下单时间')->datetime();
                $filter->between('updated_at','更新时间')->datetime();
            });
        });

        $grid->disableCreateButton();
        $grid->disableExport();
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('student_id', __('Student name'));
        $show->field('title', __('Order title'));
        $show->field('trade_no', __('Trade no'));
        $show->field('tag_price', __('Tag price'));
        $show->field('total', __('Total'));
        $show->field('refund_total', __('Refund total'));
        $show->field('statement', __('Order statement'))->using(trans("order.statuses"));
        $show->field('total_amount', __('Total amount'));
        $show->field('payment_method', __('Payment method'))->using(trans("order.pay_type"));
        $show->field('paid_at', __('Paid at'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->orderitems('订单商品详情', function ($item) {
            $item->id();
            $item->product_name();
            $item->product_count();
            $item->statement('商品状态')->using(trans("order.statuses_items"))->sortable()->label();
            $item->total();
            $item->product_cover()->image('商品图片', 50, 50);
            $item->product_price();
            $item->disableActions();
            $item->column('退款操作')->pop(function () {
                $form = new \Encore\Admin\Widgets\Form();
                $form->action('/admin/checkOrder/'.$this->row->id);
                $form->fill([
                    'id' => $this->row->id,
                    'product_name' => $this->row->product_name,
                    'product_price' => $this->row->product_price,
                    'total' => $this->row->total,
                    'product_count' => $this->row->product_count,
                    'product_cover' => $this->row->product_cover,
                    'refund_request_at' => $this->row->refund_request_at,
                    'refund_method' => $this->row->refund_method,
                    'refund_reason' => $this->row->refund_reason,
                    'refund_remark' => $this->row->refund_remark,
                    'statement' => $this->row->statement
                ]);
                $form->text('product_name',__('Product name'));
                $form->number('product_count',__('Product count'));
                $form->decimal('product_price',__('Product price'));
                $form->select('statement', __('Order statement'))->options(trans("order.statuses_items"));
              //  $form->image('product_cover',__('Product cover'));
                $form->text('refund_request_at','退款申请时间');
                $form->select('refund_method',__('退款类型'))->options(trans("order.refund_type"));
                $form->text('refund_reason',__('退款原因'));
                $form->text('refund_remark',__('退款说明'));
                $form->decimal('total','退款金额');
                $form->hidden('id',$this->row->id);
                $form->select('status', '退款审核')->options([1 => '通过', 2 => '驳回']);

                $status = ($this->row->statement == 3)?true : false;
                return new ModalForm(['name'=>'退款审核','enabled'=> $status], '退款审核', $form);
            });
            $item->filter(function (Grid\Filter $filter) {
                $filter->disableIdFilter();
                $filter->expand();
                $filter->like('product_name','商品名称');
                $filter->equal('statement','商品状态')->select(trans("order.statuses_items"));
            });
            $item->disableCreateButton();
            $item->disableExport();
        });
        $show->panel()->tools(function ($tools) {
            $tools->disableDelete();
            $tools->disableEdit();
        });
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order);

        $form->number('student_id', __('Student name'));
        $form->text('title', __('Order title'));
        $form->text('trade_no', __('Trade no'));
        $form->decimal('tag_price', __('Tag price'))->default(0.00);
        $form->decimal('total', __('Total'))->default(0.00);
        $form->decimal('refund_total', __('Refund total'))->default(0.00);
        $form->select('statement', __('Order statement'))->options(trans("order.statuses"))->default(1);
        $form->number('total_amount', __('Total amount'));
        $form->select('payment_method', __('Payment method'))->options(trans("order.pay_type"));
        $form->datetime('paid_at', __('Paid at'))->default(date('Y-m-d H:i:s'));
        $form->hasMany('orderitems','订单商品详情', function (Form\NestedForm $items) {
            $items->text('product_name',__('Product name'));
            $items->number('product_count',__('Product count'));
            $items->select('statement', __('Order statement'))->options(trans("order.statuses"))->default(1);
            $items->decimal('total',__('Total item'));
            $items->image('product_cover',__('Product cover'));
            $items->decimal('product_price',__('Product price'));
        });
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
        });
        return $form;
    }

    /**
     * 订单发货
     */
    public function deliverOrder(Request $request, Order $order)
    {
        $val = \Illuminate\Support\Facades\Validator::make($request->all(),
            [
                'express_number' => 'required'
            ], [
                'express_number.required' => "快递单号不能为空"
            ]);
        if ($val->fails()) {
            admin_toastr('快递单号不能为空','error');
            return redirect('/admin/orders');
            return redirect('/admin/deliverOrder/'.$order->id)
                ->withErrors($val)
                ->withInput();
        }
        if($order->statement !=2) admin_toastr('该订单状态，不能发货！','error');
        $order->postage()->update($request->only(['express_number']));
        $order->update(['statement' => 3]);

        admin_toastr('操作成功');
        return redirect('/admin/orders');
    }

    /**
     * 退款申请审核
     */
    public function checkOrder(Request $request, OrderItem $item)
    {
        if($item->statement !=3) admin_toastr('该订单状态，不能操作！','error');
        $val = \Illuminate\Support\Facades\Validator::make($request->all(),
            [
                'status' => 'required'
            ], [
                'status.required' => "审核状态不能为空"
            ]);
        if ($val->fails()) {
            admin_toastr('审核状态不能为空','error');
            return redirect('/admin/orders');
            return redirect('/admin/checkOrder/'.$item->id)
                ->withErrors($val)
                ->withInput();
        }
        if($request->status ==2) $item->update(['statement' => 5]);
        if($request->status ==1)
        {
            //TODO 退款流程

        }
        admin_toastr('操作成功');
        return redirect('/admin/orders/'.$item->order_id);
    }
}
