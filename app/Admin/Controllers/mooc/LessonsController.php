<?php

namespace App\Admin\Controllers\mooc;

use App\Admin\Common\Presuppose;
use App\Common\Definition;
use App\Models\baseinfo\Grade;
use App\Models\baseinfo\Semester;
use App\Models\baseinfo\Teacher;
use App\Models\mooc\Lesson;
use App\Models\mooc\LessonCategory;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class LessonsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '在线课程';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Lesson);
        $grid->quickSearch(['id', 'name', 'desc']);
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
        });

        $grid->model()->orderBy('created_at', 'desc');

        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();

            $filter->column(6, function ($filter) {
                $filter->where(function ($query) {
                    $query->whereHas('teacher', function ($query) {
                        $query->where('name', 'like', "%{$this->input}%");
                    });
                }, '讲师');

                $filter->where(function ($query) {
                    $query->whereHas('category', function ($query) {
                        $query->where('name', 'like', "%{$this->input}%");
                    });
                }, '科目');
            });

            $filter->column(6, function ($filter) {
                $filter->where(function ($query) {
                    $query->whereHas('grade', function ($query) {
                        $query->where('name', 'like', "%{$this->input}%");
                    });
                }, '年级');

                $filter->where(function ($query) {
                    $query->whereHas('semester', function ($query) {
                        $query->where('name', 'like', "%{$this->input}%");
                    });
                }, '学期');
            });

        });

        $grid->column('id', __('Id'));
        $grid->column('teacher_id', __('Teacher id'))
            ->display(function () {
                return $this->teacher->name ?? ' - ';
            });
        $grid->column('lesson_category_id', __('Lesson category id'))
            ->display(function () {
                return $this->category->name ?? ' - ';
            });

        $grid->column('semester_id', __('Semester id'))
            ->display(function () {
                return $this->semester->name ?? ' - ';
            });
        $grid->column('tag', __('Tag'))
            ->using(Definition::LESSON_TAG_EXPLAINS)
            ->label('success');
        $grid->column('name', __('Name'))
            ->display(function ($name) {
                return str_limit($name, 10, '...');
            });

        $grid->column('课程目录')
            ->display(function () {
                $link = admin_url('lesson-chapters') . '?lesson_id=' . $this->getAttribute('id');
                return "<a href='$link' class='btn btn-xs btn-primary'>查看</a>";
            });

        $grid->column('grade_id', __('Grade id'))
            ->display(function () {
                return $this->grade->name ?? ' - ';
            });
        $grid->column('price', __('Price'));
        $grid->column('list_price', __('List price'));
        $grid->column('cover', __('Cover'))
            ->lightbox(['width' => 50, 'height' => 50, 'class' => ['thumbnail']]);
        // $grid->column('images', __('Images'))
        //    ->lightbox(['width' => 50, 'height' => 50, 'class' => ['thumbnail']]);
        $grid->column('sign_dead_line', __('Sign dead line'));
        $grid->column('sign_count', __('Sign count'));
        $grid->column('view_count', __('View count'));
        $grid->column('lesson_hour_count', __('Lesson hour count'));
        $grid->column('is_streamed', __('Is streamed'))
            ->display(function ($state) {
                return $state ? "是" : "否";
            })
            ->label('default');
        $grid->column('recommended', __('Recommended'))
            ->switch(Presuppose::ON_OFF_STATES);
        $grid->column('rates', __('Rates'));
        $grid->column('详情')
            ->expand(function ($model) {
                $detail = [
                    '课程名称' => $model->getAttribute('name'),
                    '课程简介' => $model->getAttribute('desc'),
                    '课程开始日期' => $model->getAttribute('broadcast_day_begin'),
                    '课程结束日期' => $model->getAttribute('broadcast_day_end'),
                    '直播开始时间' => $model->getAttribute('broadcast_start_at'),
                    '直播结束时间' => $model->getAttribute('broadcast_ent_at'),
                ];
                return new Table([], $detail);
            });

        $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));
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
        $show = new Show(Lesson::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('teacher_id', __('Teacher id'));
        $show->field('lesson_category_id', __('Lesson category id'));
        $show->field('grade_id', __('Grade id'));
        $show->field('semester_id', __('Semester id'));
        $show->field('tag', __('Tag'));
        $show->field('name', __('Name'));
        $show->field('desc', __('Desc'));
        $show->field('price', __('Price'));
        $show->field('list_price', __('List price'));
        $show->field('sign_dead_line', __('Sign dead line'));
        $show->field('sign_count', __('Sign count'));
        $show->field('view_count', __('View count'));
        $show->field('lesson_hour_count', __('Lesson hour count'));
        $show->field('is_streamed', __('Is streamed'));
        $show->field('broadcast_day_begin', __('Broadcast day begin'));
        $show->field('broadcast_day_end', __('Broadcast day end'));
        $show->field('broadcast_start_at', __('Broadcast start at'));
        $show->field('broadcast_ent_at', __('Broadcast ent at'));
        $show->field('cover', __('Cover'));
        $show->field('images', __('Images'));
        $show->field('rates', __('Rates'));
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
        $form = new Form(new Lesson);

        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });

        $form->divider('基本信息');
        $form->text('name', __('Name'))
            ->rules('required|max:120');
        $form->select('teacher_id', __('Teacher id'))
            ->options(Teacher::pluck('name', 'id'))
            ->default(1)
            ->rules('required');
        $form->select('lesson_category_id', __('Lesson category id'))
            ->default(1)
            ->options(LessonCategory::pluck('name', 'id'))
            ->rules('required');
        $form->select('grade_id', __('Grade id'))
            ->default(1)
            ->options(Grade::pluck('name', 'id'))
            ->rules('required');
        $form->select('semester_id', __('Semester id'))
            ->default(1)
            ->options(Semester::pluck('name', 'id'))
            ->rules('required');
        $form->select('tag', __('Tag'))
            ->default(1)
            ->options(Definition::LESSON_TAG_EXPLAINS)
            ->rules('required');
        $form->editor('desc', __('Desc'))
            ->rules('required');
        $form->decimal('lesson_hour_count', __('Lesson hour count'))
            ->rules('required|integer|min:1')
            ->default(1);
        $form->decimal('price', __('Price'))
            ->default(0.00)
            ->rules('required|min:0');
        $form->decimal('list_price', __('List price'))
            ->default(0.00)
            ->rules('required|min:0');
        $form->switch('recommended', __('Recommended'))
            ->states(Presuppose::ON_OFF_STATES)
            ->default(1);
        $form->switch('status', __('Status'))
            ->states(Presuppose::SWITCH_STATES)
            ->default(1);

        $form->divider('图片资源');
        $form->image('cover', __('Cover'))
            ->removable()
            ->help('课程列表封面');
        $form->multipleImage('images', __('Images'))
            ->removable()
            ->help('课程详情展示图片');
        $form->file('prevideo', __('Prevideo'))
            ->options([
                'initialPreviewConfig' => [['filetype' => 'video/mp4']],
                'initialPreviewFileType' => 'video',
            ])
            ->removable()
            ->help('课程预览视屏');
        $form->image('preimage', __('Preimage'))
            ->removable()
            ->help('预览视频缩略图');


        $form->divider('直播信息');
        $form->switch('is_streamed', __('Is streamed'))
            ->states(Presuppose::ON_OFF_STATES)
            ->default(0);
        $form->date('sign_dead_line', __('Sign dead line'))->default(date('Y-m-d H:i:s'));
        $form->date('broadcast_day_begin', __('Broadcast day begin'))->default(date('Y-m-d H:i:s'));
        $form->date('broadcast_day_end', __('Broadcast day end'))->default(date('Y-m-d H:i:s'));
        $form->time('broadcast_start_at', __('Broadcast start at'))->default(date('Y-m-d H:i:s'));
        $form->time('broadcast_ent_at', __('Broadcast ent at'))->default(date('Y-m-d H:i:s'));

        $form->divider('其他');
        $form->decimal('sign_count', __('Sign count'))
            ->rules('integer|min:0')
            ->default(0);
        $form->decimal('view_count', __('View count'))
            ->rules('integer|min:0')
            ->default(0);
        $form->decimal('rates', __('Rates'))
            ->rules('numeric|min:0|max:5')
            ->default(5);


//        $form
//            ->tab('基本信息', function (Form $form) {
//            })
//            ->tab('图片资源', function (Form $form) {
//            })
//            ->tab('直播课程', function (Form $form) {
//            })
//            ->tab('其他', function (Form $form) {
//            });

        return $form;
    }
}
