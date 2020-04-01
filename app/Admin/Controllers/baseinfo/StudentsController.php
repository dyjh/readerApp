<?php

namespace App\Admin\Controllers\baseinfo;

use App\Admin\Common\Presuppose;
use App\Common\Definition;
use App\Models\baseinfo\Ban;
use App\Models\baseinfo\Grade;
use App\Models\baseinfo\Student;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class StudentsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '会员管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Student);
        $grid->disableCreateButton();
        $grid->quickSearch('name', 'school_name', 'ban_name', 'phone');

        $grid->model()->orderBy('created_at', 'desc');

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('avatar', __('Avatar'))->lightbox(['width' => 50, 'height' => 50, 'class' => ['circle', 'thumbnail']]);
        $grid->column('total_beans', __('Total beans'));
        $grid->column('phone', __('Phone'));
        $grid->column('city', __('City'));
        $grid->column('province', __('Province'));
        $grid->column('district', __('District'));
        $grid->column('school_name', __('School name'));
        $grid->column('grade_name', __('Grade name'));
        $grid->column('ban_name', __('Ban name'));
        $grid->column('read_count', __('Read count'))
            ->sortable()
            ->help('用户图书阅读数量');
        $grid->column('share_count', __('Share count'))
            ->sortable()
            ->help('用户上传书籍数量');
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
//        $grid->column('status', __('Status'))
//            ->switch(Presuppose::SWITCH_STATES)
//            ->sortable()
//            ->help('启用/禁用该用户');

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
        $show = new Show(Student::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('avatar', __('Avatar'));
        $show->field('total_beans', __('Total beans'));
        $show->field('phone', __('Phone'));
        $show->field('city', __('City'));
        $show->field('province', __('Province'));
        $show->field('district', __('District'));
        $show->field('school_id', __('School id'));
        $show->field('school_name', __('School name'));
        $show->field('grade_id', __('Grade id'));
        $show->field('grade_name', __('Grade name'));
        $show->field('ban_id', __('Ban id'));
        $show->field('ban_name', __('Ban name'));
        $show->field('read_count', __('Read count'));
        $show->field('share_count', __('Share count'));
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
        $form = new Form(new Student);

        $form->text('name', __('Name'));
        $form->cropper('avatar', __('Avatar'))
            ->cRatio(96, 96);;
        $form->text('total_beans', __('Total beans'))
            ->rules('integer');
        // $form->password('password', __('Password'));
        $form->mobile('phone', __('Phone'));
        // $form->distpicker(['province_id', 'city_id', 'district_id']);
        $form->display('city', __('City'));
        $form->display('province', __('Province'));
        $form->display('district', __('District'));
        // $form->number('school_id', __('School id'));
        // $form->text('school_name', __('School name'));
        $form->select('grade_id', __('Grade name'))
            ->options(Grade::pluck('name', 'id'))
            ->disable();
        $form->select('ban_id', __('Ban name'))
            ->options(Ban::pluck('name', 'id'))
            ->disable();
        $form->text('read_count', __('Read count'))
            ->rules('integer');
        $form->text('share_count', __('Share count'))
            ->rules('integer');
        $form->switch('status', __('Status'))
            ->states(Presuppose::SWITCH_STATES)
            ->default(1);

        return $form;
    }
}
