<?php

namespace App\Admin\Controllers\mooc;

use App\Admin\Extensions\tools\Backforwad;
use App\Models\mooc\Lesson;
use App\Models\mooc\LessonCatalog;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;

class LessonCatalogsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '课程目录';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new LessonCatalog);

        $grid->column('id', __('Id'));
        $grid->column('lesson_id', __('Lesson id'))
            ->display(function () {
                return $this->lesson->name ?? ' - ';
            });
        $grid->column('name', __('Name'));
        $grid->column('desc', __('Desc'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('created_at', __('Created at'));

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
        $show = new Show(LessonCatalog::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('lesson_id', __('Lesson id'));
        $show->field('name', __('Name'));
        $show->field('desc', __('Desc'));
        $show->field('deleted_at', __('Deleted at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('created_at', __('Created at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new LessonCatalog);
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
            $tools->disableList();
            $tools->disableDelete();
            $tools->append(new Backforwad());
        });

        $form->disableCreatingCheck();
        $form->disableEditingCheck();
        $form->disableViewCheck();

        $form->select('lesson_id', __('Lesson id'))
            ->options(Lesson::pluck('name', 'id'))
            ->readOnly();
        $form->text('name', __('Name'));
        $form->textarea('desc', __('Desc'));

        return $form;
    }

    public function ajaxEditModal(LessonCatalog $catalog)
    {
        $form = new \Encore\Admin\Widgets\Form();
        $form->action(admin_url('branches-edit'));
        $form->fill([
            'lesson' => $catalog->lesson->name,
            'id' => $catalog->getAttribute('id'),
            'name' => $catalog->getAttribute('name'),
            'desc' => $catalog->getAttribute('desc')
        ]);

        $form->hidden('id');
        $form->display('lesson', __('Lesson id'));
        $form->text('name', __('Name'))
            ->rules('required|max:120');
        $form->textarea('desc', __('Desc'))
            ->rules('required|max:120');

        return $form->render();
    }

    public function ajaxEdit(Request $request)
    {
        $catalog = LessonCatalog::findOrFail($request->post('id'));

        $name = $request->post('name');
        $desc = $request->post('desc');

        $catalog->update(compact('name', 'desc'));


        admin_toastr('目录修改成功');
        return back();
    }

    public function saveFormModal()
    {
        $data = \request()->all();
        $attribute = [
            'lesson_id' => $data['lesson_id'],
            'name' => $data['name'],
            'desc' => $data['desc']
        ];

        $catalog = LessonCatalog::create($attribute);
        if (!$catalog) {
            admin_toastr('操作失败', 'error');
            return redirect()->back();
        }

        admin_toastr('操作成功');
        return redirect()->back();
    }

    public function saveOrders(Request $request)
    {
        $attributes = $request->post('_order');
        $attributes = json_decode($attributes, true);
        LessonCatalog::saveCatalogOrder($attributes);
        return response()->json([
            'status' => true
        ]);
    }
}
