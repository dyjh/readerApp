<?php

namespace App\Admin\Controllers\platform;

use App\Admin\Common\Presuppose;
use App\Common\Definition;
use App\Models\mooc\Lesson;
use App\Models\platform\Banner;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BannersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '轮播图设置';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Banner);
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
        });
        $grid->sortable();

        $grid->column('id', __('Id'))
            ->help('拖动ID调整排序');
        $grid->column('type', __('Type'))
            ->using(Definition::BANNER_TYPE_EXPLAINS);
        $grid->column('cover')
            ->lightbox(['width' => 50, 'height' => 50, 'class' => ['thumbnail']]);
        $grid->column('href', __('Href'));
        // $grid->column('content', __('Content'));
        $grid->column('recommend_lesson_id', __('Recommend lesson id'))
            ->display(function () {
                return $this->recommendLesson->name ?? ' - ';
            });
        // $grid->column('sort', __('Sort'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('status', __('Status'))
            ->switch(Presuppose::ON_OFF_STATES);

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
        $show = new Show(Banner::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('type', __('Type'));
        $show->field('href', __('Href'));
        $show->field('content', __('Content'));
        $show->field('recommend_lesson_id', __('Recommend lesson id'));
        $show->field('sort', __('Sort'));
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
        $form = new Form(new Banner);

        $form->cropper('cover', '轮播图');
//            ->cRatio();
        $form->select('type', __('Type'))
            ->options(Definition::BANNER_TYPE_EXPLAINS)
            ->required();
        $form->url('href', __('Href'))
            ->help('外部链接: 类型为<b>链接</b>时必填');
        $form->editor('content', __('Content'))
            ->help('图文内容: 类型为<b>图文</b>时必填');
        $form->select('recommend_lesson_id', __('Recommend lesson id'))
            ->options(Lesson::where('recommended', 1)->pluck('name', 'id'))
            ->help('图文内容: 类型为<b>推荐课程</b>时必填');
        // $form->number('sort', __('Sort'));
        $form->switch('status', __('Status'))->default(1)
            ->states(Presuppose::ON_OFF_STATES);

        return $form;
    }
}
