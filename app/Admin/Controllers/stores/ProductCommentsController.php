<?php

namespace App\Admin\Controllers\stores;

use App\Models\stores\ProductBookComment;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProductCommentsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商品评论';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ProductBookComment);

        $grid->model()->orderBy('created_at', 'desc');

        $grid->column('id', __('Id'));
        $grid->column('order_id', __('Order number'))->display(function (){return $this->order->trade_no ?? '';});
        $grid->column('product_book_id', __('Product book id'))->display(function (){return $this->product->name ?? '';});
        $grid->column('student_id',__('Student name'))->display(function (){return $this->user->name ?? '';});
        $grid->column('desc_match_rate', __('Desc match rate'));
        $grid->column('service_attitude_rate', __('Service attitude rate'));
        $grid->column('content', __('Content'))->display(function ($value) {
            return str_limit($value, 20, '...');
        });
        $grid->column('created_at', __('Created at'));

        $grid->actions(function ($actions) {
            $actions->disableEdit();
            $actions->disableView();
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
        $show = new Show(ProductBookComment::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('order_id', __('Order number'));
        $show->field('product_book_id', __('Product book id'))->display(function (){return $this->product->name ?? '';});
        $show->field('student_id', __('Student name'))->display(function (){return $this->user->name ?? '';});
        $show->field('desc_match_rate', __('Desc match rate'));
        $show->field('service_attitude_rate', __('Service attitude rate'));
        $show->field('content', __('Content'));
        $show->field('created_at', __('Created at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ProductBookComment);

        $form->number('order_id', __('Order id'));
        $form->number('product_book_id', __('Product book id'));
        $form->number('student_id', __('Student id'));
        $form->decimal('desc_match_rate', __('Desc match rate'))->default(5.0);
        $form->decimal('service_attitude_rate', __('Service attitude rate'))->default(5.0);
        $form->text('content', __('Content'));

        return $form;
    }
}
