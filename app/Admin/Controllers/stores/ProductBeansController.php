<?php

namespace App\Admin\Controllers\stores;

use App\Admin\Common\Presuppose;
use App\Models\baseinfo\ProductBean;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProductBeansController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '书豆商品';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ProductBean);
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
        });

        $grid->model()->orderBy('created_at', 'desc');
        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('amount', __('Amount'));
        $grid->column('price', __('Price'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('status', __('Status'))
            ->switch(Presuppose::SWITCH_STATES);
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
        $show = new Show(ProductBean::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('amount', __('Amount'));
        $show->field('price', __('Price'));
        $show->field('status', __('Status'));
        $show->field('deleted_at', __('Deleted at'));
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
        $form = new Form(new ProductBean);

        $form->text('name', __('Name'))
            ->rules('required|max:10');
        $form->decimal('amount', __('Amount'))
            ->rules('required|integer');
        $form->decimal('price', __('Price'))
            ->rules('required')
            ->default(0.00);
        $form->switch('status', __('Status'))
            ->states(Presuppose::ON_OFF_STATES)
            ->default(1);
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });
        return $form;
    }
}
