<?php

namespace App\Admin\Controllers\stores;

use App\Admin\Common\Presuppose;
use App\Models\stores\ProductBook;
use App\Models\stores\ProductCategory;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;
use Encore\WangEditor\Editor;
use function foo\func;

class ProductsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商品管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ProductBook);

        $grid->model()
            ->orderBy('on_sale', 'desc')
            ->orderBy('created_at', 'desc');

        $grid->column('id', __('Id'));
        $grid->column('product_category_id', __('Product category id'))->display(function () {
            return $this->category->name ?? '';
        });
        $grid->column('name', __('Name'));
        $grid->column('isbn', __('Isbn'));
        $grid->column('cover', __('Cover'))->lightbox(['width' => 50, 'height' => 50, 'class' => ['thumbnail']]);
        $grid->column('商品详情')
            ->expand(function (ProductBook $model) {
                $detail = [
                    '作者' => $model->getAttribute('author'),
                    '出版社' => $model->getAttribute('publisher'),
                    '描述' => $model->getAttribute('info_text'),
                ];
                return new Table([], $detail);
            });
        $grid->column('stock', __('Stock'));
        $grid->column('tag_price', __('Tag price'));
        $grid->column('sell_price', __('Sell price'));
        $grid->column('discount', __('Discount'));
        $grid->column('sales', __('Sales'));
        $grid->column('rates', __('Rates'));
        $grid->column('on_sale', __('On sale'))
            ->switch(Presuppose::SALE_STATES)
            ->sortable()
            ->help('商品上架状态');
        $grid->column('comment_counts', __('Comment counts'));
//        $grid->column('deleted_at', __('Deleted at'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->quickSearch('name');
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
        });
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
        $show = new Show(ProductBook::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('product_category_id', __('Product category id'));
        $show->field('name', __('Name'));
        $show->field('author', __('Author'));
        $show->field('publisher', __('Publisher'));
        $show->field('isbn', __('Isbn'));
        $show->field('cover', __('Cover'))->image();
//        $show->field('videos', __('Videos'));
        $show->field('info_images', __('Info images'))->carousel();
        $show->field('info_text', __('Info text'));
        $show->field('stock', __('Stock'));
        $show->field('tag_price', __('Tag price'));
        $show->field('sell_price', __('Sell price'));
        $show->field('discount', __('Discount'));
        $show->field('sales', __('Sales'));
        $show->field('rates', __('Rates'));
        $show->field('content', __('Content'));
        $show->field('on_sale', __('On sale'));
        $show->field('comment_counts', __('Comment counts'));
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
        $form = new Form(new ProductBook);
        $form->text('name', __('Name'))->rules('required|string|max:50');
        $form->text('author', __('Author'))->rules('required|string|max:50');
        $form->text('publisher', __('Publisher'))->rules('required|string|max:50');
        $form->select('product_category_id', __('Product category id'))
            ->options(ProductCategory::pluck('name', 'id'))->rules('required|integer');
        $form->text('isbn', __('Isbn'))->rules('required|string|max:50');
        $form->image('cover', __('Cover'));
//        $form->multipleFile('videos', __('Videos'))->removable()->options([
//            //'initialPreviewConfig' => [['filetype' => 'video']],
//            'initialPreviewFileType' => 'video',
//        ]);
        $form->file('videos->prevideo', __('Prevideo'))
            ->options([
                'initialPreviewFileType' => 'video',
            ]);
        $form->image('videos->preimage', __('Preimage'));
        $form->multipleImage('info_images', __('Info images'))->removable();
        $form->text('info_text', __('Info text'))->rules('max:250')->default('');
        $form->number('stock', __('Stock'))->rules('required|integer')->default(0);
        $form->decimal('tag_price', __('Tag price'))->default(0.00);
        $form->decimal('sell_price', __('Sell price'))->default(0.00);
        $form->decimal('discount', __('Discount'))->default(0.00);
        $form->number('sales', __('Sales'))->default(0);
        $form->decimal('rates', __('Rates'))->default(5.0);
        $form->editor('content', __('Content'));
        $form->switch('on_sale', __('On sale'))->default(1);
        $form->number('comment_counts', __('Comment counts'))->default(0);
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });
        return $form;
    }
}
