<?php

namespace App\Admin\Controllers\shares;

use App\Common\Definition;
use App\Models\shares\StudentsBooksRent;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class StudentsBooksRentsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '借书记录';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new StudentsBooksRent);
        $grid->disableCreateButton();
        $grid->disableActions();
        $grid->quickSearch('renter_name', 'lender_name', 'shared_book_name');

        $grid->model()->orderBy('created_at', 'desc');

        $grid->column('id', __('Id'));
        $grid->column('shared_book_name', __('Shared book name'));
        $grid->column('shared_book_cover', __('Shared book cover'))
            ->lightbox(['width' => 50, 'height' => 50, 'class' => ['thumbnail']]);
        $grid->column('renter_name', __('Renter name'));
        $grid->column('lender_name', __('Lender name'));
        $grid->column('statement', __('Statement'))
            ->using(Definition::SHARED_BOOK_RENT_STATE_EXPLAINS)
            ->label('default');
        $grid->column('rend_applied_at', __('Rend applied at'));
        $grid->column('rend_canceled_at', __('Rend canceled at'));
        $grid->column('rend_allowed_at', __('Rend allowed at'));
        $grid->column('rend_rejected_at', __('Rend rejected at'));
        $grid->column('return_applied_at', __('Return applied at'));
        $grid->column('return_confirm_at', __('Return confirm at'));
        $grid->column('cast_beans', __('Cast beans'));
        $grid->column('over_limit_days', __('Over limit days'));

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
        $show = new Show(StudentsBooksRent::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('renter_name', __('Renter name'));
        $show->field('lender_name', __('Lender name'));
        $show->field('shared_book_name', __('Shared book name'));
        $show->field('shared_book_cover', __('Shared book cover'));
        $show->field('statement', __('Statement'));
        $show->field('rend_applied_at', __('Rend applied at'));
        $show->field('rend_allowed_at', __('Rend allowed at'));
        $show->field('rend_rejected_at', __('Rend rejected at'));
        $show->field('return_applied_at', __('Return applied at'));
        $show->field('return_confirm_at', __('Return confirm at'));
        $show->field('cast_beans', __('Cast beans'));
        $show->field('over_limit_days', __('Over limit days'));
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
        $form = new Form(new StudentsBooksRent);

        $form->number('private_book_id', __('Private book id'));
        $form->text('renter_name', __('Renter name'));
        $form->text('lender_name', __('Lender name'));
        $form->text('shared_book_name', __('Shared book name'));
        $form->text('shared_book_cover', __('Shared book cover'));
        $form->switch('statement', __('Statement'))->default(1);
        $form->datetime('rend_applied_at', __('Rend applied at'))->default(date('Y-m-d H:i:s'));
        $form->datetime('rend_allowed_at', __('Rend allowed at'))->default(date('Y-m-d H:i:s'));
        $form->datetime('rend_rejected_at', __('Rend rejected at'))->default(date('Y-m-d H:i:s'));
        $form->datetime('return_applied_at', __('Return applied at'))->default(date('Y-m-d H:i:s'));
        $form->datetime('return_confirm_at', __('Return confirm at'))->default(date('Y-m-d H:i:s'));
        $form->number('cast_beans', __('Cast beans'));
        $form->number('over_limit_days', __('Over limit days'));

        return $form;
    }
}
