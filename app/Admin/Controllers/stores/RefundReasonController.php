<?php

namespace App\Admin\Controllers\stores;

use App\Admin\Common\Presuppose;
use App\Models\stores\RefundReason;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class RefundReasonController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '退款原因管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new RefundReason);

        $grid->model()->orderBy('created_at', 'desc');

        $grid->column('id', __('Id'));
        $grid->column('title', __('RefundReason Title'));
        $grid->column('describe', __('Describe'));
        $grid->column('status', __('Status'))
            ->switch(Presuppose::SWITCH_STATES)
            ->sortable()
            ->help('状态');
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->disableExport();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
        });
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
        $show = new Show(RefundReason::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('RefundReason Title'));
        $show->field('describe', __('Describe'));
        $show->field('status', __('Status'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new RefundReason);

        $form->text('title', __('RefundReason Title'))->rules('required|string|max:50');
        $form->text('describe', __('Describe'))->rules('max:250');
        $form->switch('status', __('Status'))->default(1);
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });

        dd($form->describe);
        return $form;
    }
}
