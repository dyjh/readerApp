<?php

namespace App\Admin\Controllers\baseinfo;

use App\Admin\Common\Presuppose;
use App\Models\baseinfo\School;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SchoolsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '学校管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new School);
        $grid->quickSearch(['name', 'city', 'province', 'district', 'telephone']);

        $grid->model()->orderBy('created_at', 'desc');

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('city', __('City'));
        $grid->column('province', __('Province'));
        $grid->column('district', __('District'));
        $grid->column('telephone', __('Telephone'));
        $grid->column('school_type', __('School type'));
        $grid->column('approach', __('Approach'));
        $grid->column('special', __('Special'));
        $grid->column('address', __('Address'));
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
        $show = new Show(School::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('city', __('City'));
        $show->field('province', __('Province'));
        $show->field('district', __('District'));
        $show->field('telephone', __('Telephone'));
        $show->field('school_type', __('School type'));
        $show->field('approach', __('Approach'));
        $show->field('special', __('Special'));
        $show->field('address', __('Address'));
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
        $form = new Form(new School);

        $form->text('name', __('Name'))
            ->rules('required|max:50');
        $form->text('city', __('City'))
            ->rules('required|max:20');
        $form->text('province', __('Province'))
            ->rules('required|max:20');
        $form->text('district', __('District'))
            ->rules('required|max:20');
        $form->text('telephone', __('Telephone'))
            ->rules('required|max:50');
        $form->text('school_type', __('School type'));
        $form->text('approach', __('Approach'));
        $form->textarea('special', __('Special'));
        $form->text('address', __('Address'));
        $form->switch('status', __('Status'))
            ->states(Presuppose::SWITCH_STATES)
            ->default(1);

        return $form;
    }
}
