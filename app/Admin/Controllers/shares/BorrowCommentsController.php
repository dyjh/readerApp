<?php

namespace App\Admin\Controllers\shares;

use App\Models\shares\BorrowComment;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class BorrowCommentsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '借阅评论';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BorrowComment);
        $grid->quickSearch('student_name', 'shared_book_name');
        $grid->disableCreateButton();
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
        });

        $grid->model()->orderBy('created_at', 'desc');

        $grid->column('id', __('Id'));
        $grid->column('student_name', __('Student name'));
        $grid->column('student_avatar', __('Student avatar'))
            ->lightbox(['width' => 50, 'height' => 50, 'class' => ['thumbnail']]);
        $grid->column('shared_book_name', __('Shared book name'))
            ->display(function ($value) {
                return str_limit($value, 20, '...');
            });
        $grid->column('content', __('Content'))
            ->display(function ($value) {
                return str_limit($value, 20, '...');
            });

        $grid->column('详情')
            ->expand(function ($model) {
                $detail = [
                    '书名' => $model->getAttribute('shared_book_name'),
                    '评论' => $model->getAttribute('content'),
                ];
                return new Table([], $detail);
            });
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(BorrowComment::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('shared_book_name', __('Shared book name'));
        $show->field('student_name', __('Student name'));
        $show->field('student_avatar', __('Student avatar'));
        $show->field('content', __('Content'));
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
        $form = new Form(new BorrowComment);

        $form->text('shared_book_name', __('Shared book name'));
        $form->text('student_name', __('Student name'));
        $form->image('student_avatar', __('Student avatar'));
        $form->textarea('content', __('Content'));

        return $form;
    }
}
