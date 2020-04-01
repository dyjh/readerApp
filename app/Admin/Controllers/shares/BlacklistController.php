<?php

namespace App\Admin\Controllers\shares;

use App\Common\Definition;
use App\Models\baseinfo\Student;
use App\Models\shares\StudentsBooksRent;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;
use Illuminate\Http\Request;

class BlacklistController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '借书黑名单';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Student);

        $grid->model()->orderBy('created_at', 'desc');

        $grid->disableActions();
        $grid->quickSearch('name', 'school_name', 'ban_name');
        $grid->model()
            ->where('in_blacklist', 1)
            ->orderBy('created_at', 'desc');
        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('avatar', __('Avatar'))
            ->lightbox(['width' => 50, 'height' => 50, 'class' => ['circle', 'thumbnail']]);
        $grid->column('phone', __('Phone'));
        $grid->column('province', __('Province'));
        $grid->column('city', __('City'));
        $grid->column('district', __('District'));
        $grid->column('school_name', __('School name'));
        $grid->column('grade_name', __('Grade name'));
        $grid->column('ban_name', __('Ban name'));
        $grid->column('total_beans', __('Total beans'))
            ->label('primary');

        $grid->column('未归还书籍')
            ->expand(function ($model) {
                $detail = [];
                $unreturned = StudentsBooksRent::where([
                    'renter_id' =>$model->getAttribute('id'),
                    'blacked' => 1
                ])->get();
                foreach ($unreturned as $item) {
                    $detail[] = [
                        $item->getAttribute('shared_book_name'),
                        Definition::SHARED_BOOK_RENT_STATE_EXPLAINS[$item->getAttribute('statement')] ?? ' - ',
                        $item->getAttribute('rend_allowed_at'),
                        $item->getAttribute('over_limit_days'),
                    ];
                }
                return new Table(['书名', '借书状态', '借书时间', '超过还书时间'], $detail);
            });
        $grid->column('updated_at', __('Updated at'));

        $grid->column('移除黑名单')
            ->button('primary')
            ->display(function () {
                $studentId = $this->getAttribute('id');
                $studentName = $this->getAttribute('name');

                $link = admin_url('blacklist/remove');

                $script = <<<EOT
$('.freeLocker').on('click', function () {
    
    var studentId = $(this).data('id');
    var studentName = $(this).data('name');
    
    swal({
        title: "确认要把 " + studentName + " 移出借书黑名单吗?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "确定",
        cancelButtonText: "取消"
    }).then(function (choose) {
        console.log(choose);
        if (choose.value === true) {
        
            $.post('{$link}', {
                _token: LA.token,
                student_id: studentId
            },
            function(data){
                $.pjax.reload('#pjax-container');
                toastr.success('顺序保存成功');
            });
        }
    });
});
EOT;
                Admin::script($script);
                return "<button data-id='{$studentId}' data-name='{$studentName}'  class='btn btn-xs btn-success freeLocker'>移出</button>";
            });

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
        $show->field('password', __('Password'));
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
        $show->field('in_blacklist', __('In blacklist'));
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
        $form->image('avatar', __('Avatar'));
        $form->number('total_beans', __('Total beans'));
        $form->password('password', __('Password'));
        $form->mobile('phone', __('Phone'));
        $form->text('city', __('City'));
        $form->text('province', __('Province'));
        $form->text('district', __('District'));
        $form->number('school_id', __('School id'));
        $form->text('school_name', __('School name'));
        $form->number('grade_id', __('Grade id'));
        $form->text('grade_name', __('Grade name'));
        $form->number('ban_id', __('Ban id'));
        $form->text('ban_name', __('Ban name'));
        $form->number('read_count', __('Read count'));
        $form->number('share_count', __('Share count'));
        $form->switch('in_blacklist', __('In blacklist'));
        $form->switch('status', __('Status'))->default(1);

        return $form;
    }

    /**
     * @param Request $request
     * @author marhone
     */
    public function remove(Request $request)
    {
        $studentId = $request->post('student_id');
        /** @var Student $student */
        $student = Student::findOrFail($studentId);
        $student->update([
            'in_blacklist' => 0
        ]);
    }
}
