<?php

namespace App\Admin\Controllers\baseinfo;

use App\Admin\Common\Presuppose;
use App\Models\baseinfo\Teacher;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class TeachersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '讲师管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Teacher);

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
        });

        $grid->model()->orderBy('created_at', 'desc');

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('avatar', __('Avatar'))
            ->lightbox(['width' => 50, 'height' => 50, 'class' => ['circle', 'thumbnail']]);
        $grid->column('title', __('Title'));
        $grid->column(__('Profile'))
            ->expand(function ($model) {
                $detail = [
                    '简介' => $model->getAttribute('profile'),
                ];
                return new Table([], $detail);
            });
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('status', __('Status'))
            ->switch(Presuppose::SWITCH_STATES);

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
        $show = new Show(Teacher::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('avatar', __('Avatar'));
        $show->field('title', __('Title'));
        $show->field('profile', __('Profile'));
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
        $form = new Form(new Teacher);

        $form->text('name', __('Name'))->rules('required|max:20');
        $form->cropper('avatar', __('Avatar'))
            ->cRatio(96, 96);
        $form->text('title', __('Title'))->rules('required|max:20');
        $form->textarea('profile', __('Profile'))->rules('required|max:150');
        $form->switch('status', __('Status'))
            ->states(Presuppose::SWITCH_STATES)
            ->default(1);

        return $form;
    }
}
