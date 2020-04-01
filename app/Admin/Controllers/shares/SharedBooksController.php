<?php

namespace App\Admin\Controllers\shares;

use App\Admin\Common\Presuppose;
use App\Models\shares\SharedBook;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class SharedBooksController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '共享书籍';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SharedBook);
        $grid->disableCreateButton();
        $grid->quickSearch('name', 'author', 'publisher');

        $grid->model()->orderBy('created_at', 'desc');

        $grid->column('id', __('Id'));
        $grid->column('cover', __('Cover'))
            ->lightbox(['width' => 50, 'height' => 50, 'class' => ['thumbnail']]);
        $grid->column('name', __('Name'))
            ->display(function ($name) {
                return str_limit($name, 20, '...');
            });
        $grid->column('isbn', __('Isbn'));
        $grid->column('图书详情')
            ->expand(function (SharedBook $model) {
                $detail = [
                    '作者' => $model->getAttribute('author'),
                    '出版社' => $model->getAttribute('publisher'),
                    '描述' => $model->getAttribute('desc'),
                ];
                return new Table([], $detail);
            });
        $grid->column('rent_counts', __('Rent counts'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('status', __('Status'))
            ->switch(Presuppose::SWITCH_STATES)
            ->sortable();

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
        $show = new Show(SharedBook::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('author', __('Author'));
        $show->field('publisher', __('Publisher'));
        $show->field('isbn', __('Isbn'));
        $show->field('cover', __('Cover'));
        $show->field('desc', __('Desc'));
        $show->field('rent_counts', __('Renters count'));
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
        $form = new Form(new SharedBook);

        $form->text('name', __('Name'));
        $form->text('author', __('Author'));
        $form->text('publisher', __('Publisher'));
        $form->display('isbn', __('Isbn'));
        $form->image('cover', __('Cover'));
        $form->textarea('desc', __('Desc'));
        $form->number('rent_counts', __('Rent counts'));
        $form->switch('status', __('Status'))->options(Presuppose::SWITCH_STATES)->default(1);

        return $form;
    }
}
