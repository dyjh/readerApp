<?php

namespace App\Admin\Controllers\mooc;

use App\Admin\Common\Presuppose;
use App\Admin\Extensions\tools\Backforwad;
use App\Admin\Extensions\tools\CreationButton;
use App\Admin\Extensions\widgets\Modal;
use App\Admin\Extensions\widgets\TreeViewer;
use App\Models\mooc\Lesson;
use App\Models\mooc\LessonCatalog;
use App\Models\mooc\LessonChapter;
use App\Services\mooc\QiniuStreamService;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Show;
use Encore\Admin\Tree;
use Encore\Admin\Widgets\Table;
use Illuminate\Http\Request;

class LessonChaptersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '课程小节';

    /**
     * Index interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content)
    {
        $lessonId = request()->get('lesson_id');
        $lesson = Lesson::findOrFail($lessonId);
        return $content
            ->title($lesson->getAttribute('name'))
            ->description('课程小节列表')
            ->body($this->grid($lessonId));
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     *
     * @return Content
     */
    public function show($id, Content $content)
    {
        $this->setMetaInfo($id);
        return $content
            ->title($this->title())
            ->description($this->description['show'] ?? trans('admin.show'))
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        $this->setMetaInfo($id);
        return $content
            ->title($this->title())
            ->description($this->description['create'] ?? trans('admin.create'))
            ->row(function (Row $row) use ($id) {
                $lessonId = request()->get('lesson_id');
                if ($lessonId) {
                    $row->column(4, function (Column $column) use ($lessonId) {
                        $column->append($this->catalogList($lessonId));
                    });
                }
                $row->column(8, function (Column $column) use ($id) {
                    $modal = $this->catalogAdd(request()->get('lesson_id'));
                    $column->append($this->form()->edit($id));
                    $column->append($modal->render());
                });
            });
    }

    /**
     * Create interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function create(Content $content)
    {
        $this->setMetaInfo();
        return $content
            ->title($this->title())
            ->description($this->description['create'] ?? trans('admin.create'))
            ->row(function (Row $row) {
                $row->column(4, function (Column $column) {
                    $column->append($this->catalogList(request()->get('lesson_id')));
                });

                $row->column(8, function (Column $column) {
                    $modal = $this->catalogAdd(request()->get('lesson_id'));
                    $column->append($this->form());
                    $column->append($modal->render());
                });
            });
    }

    /**
     * 设置标题
     * @param $id
     * @param null $parentId
     * @author marhone
     */
    protected function setMetaInfo($id = null)
    {
        if (is_null($id)) {
            $lessonId = request()->get('lesson_id');
            $lesson = Lesson::findOrFail($lessonId);
            $this->title = $lesson->getAttribute('name');
            $this->description['index'] = $lesson->getAttribute('desc');
            $this->description['create'] = '创建课程小节';
        }

        $chapter = LessonChapter::with('lesson')->find($id);
        if ($chapter && $chapter->has('lesson')) {
            $this->title = $chapter->lesson->name;
            $this->description['show'] = $chapter->name;
            $this->description['edit'] = $chapter->name;
        }
    }

    protected function catalogList($lessonId)
    {
        $model = new LessonCatalog();
        $catalog = new TreeViewer($model, function (TreeViewer $tree) use ($lessonId) {
            $tree->branch(function ($branch) use ($lessonId) {
                return "<strong>{$branch['name']}</strong>";
            });
        });
        return $catalog;
    }

    protected function catalogAdd($lessonId)
    {
        $form = new \Encore\Admin\Widgets\Form();
        $form->action(admin_url('lesson-catalog-add'));
        $form->fill([
            'lesson_id' => $lessonId
        ]);

        $form->select('lesson_id', __('Lesson id'))
            ->options(Lesson::pluck('name', 'id'))
            ->readOnly();
        $form->text('name', __('Name'))
            ->rules('required|max:120');
        $form->textarea('desc', __('Desc'))
            ->rules('required|max:120');

        $modal = new Modal('catalogModal', '添加章节', $form->render());
        return $modal;
    }

    /**
     * Make a grid builder.
     *
     * @param $lessonId
     * @return Grid
     */
    protected function grid($lessonId)
    {
        $grid = new Grid(new LessonChapter);
        $grid->disableCreateButton();
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
            $actions->disableEdit();
        });
        $grid->tools(function (Grid\Tools $tools) use ($lessonId) {
            $link = admin_url('lesson-chapters/create') . '?lesson_id=' . $lessonId;
            $tools->append(new CreationButton($link));
            $tools->append(new Backforwad());
        });

        $grid->model()->where('lesson_id', $lessonId);

        $grid->column('id', __('Id'));
        $grid->column('lesson_id', __('Lesson id'))
            ->display(function () {
                return $this->lesson->name ?? ' - ';
            });
        $grid->column('catalog_id', __('Catalog id'))
            ->display(function () {
                return $this->catalog->name ?? ' - ';
            });
        $grid->column('name', __('Name'));
        $grid->column('broadcast_day', __('Broadcast day'))
            ->display(function ($value) {
                return $this->getAttribute('is_streamed') ? $value : ' - ';
            });
        $grid->column('broadcast_start_at', __('Broadcast start at'))
            ->display(function ($value) {
                return $this->getAttribute('is_streamed') ? $value : ' - ';
            });
        $grid->column('broadcast_ent_at', __('Broadcast ent at'))
            ->display(function ($value) {
                return $this->getAttribute('is_streamed') ? $value : ' - ';
            });
        $grid->column('is_streamed', __('Is streamed'))
            ->display(function ($state) {
                return $state ? "是" : "否";
            })
            ->label('default');
        $grid->column('查看推流地址')
            ->expand(function () {
                $detail = [
                    '推流地址' => $this->getAttribute('rtmp_publish_url'),
                ];
                return new Table([], $detail);
            });

        $grid->column('推流地址')
            ->display(function () {
                $rtmpUrl = $this->getAttribute('rtmp_publish_url');

                $buttonName = $rtmpUrl ? '重新生成' : '生成';

                $link = admin_url('lesson-chapter/publish');
                $script = <<<JS
$('.gen').on('click', function () {
    
    var studentId = $(this).data('id');
    
    swal({
        title: "确认要{$buttonName}直播推流地址吗?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "确定",
        cancelButtonText: "取消"
    }).then(function (choose) {
        console.log(choose);
        if (choose.value === true) {
        
            $.post('{$link}/' + studentId, {
                _token: LA.token,
            },
            function(data){
                $.pjax.reload('#pjax-container');
                if (data.status) {
                    toastr.success(data.message);
                } else {
                    toastr.error(data.message);
                }
            });
        }
    });
});
JS;

                Admin::script($script);
                return "<button class='btn btn-primary btn-xs gen' data-id='{$this->getAttribute('id')}'>$buttonName</button>";
            });

        $grid->column('created_at', __('Created at'));
        $grid->column('编辑')
            ->display(function () use ($lessonId) {
                $link = admin_url("/lesson-chapters/{$this->id}/edit?lesson_id={$lessonId}");
                return "<a href='{$link}' class='btn btn-xs btn-primary'>编辑</a>";
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
        $show = new Show(LessonChapter::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('lesson_id', __('Lesson id'));
        $show->field('catalog_id', __('Catalog id'));
        $show->field('name', __('Name'));
        $show->field('broadcast_day', __('Broadcast day'));
        $show->field('broadcast_start_at', __('Broadcast start at'));
        $show->field('broadcast_ent_at', __('Broadcast ent at'));
        $show->field('play_back_url', __('Play back url'));
        $show->field('is_streamed', __('Is streamed'));
        //  $show->field('stream_url', __('Stream url'));
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
        $form = new Form(new LessonChapter);
        $form->setTitle('添加课程小节');

        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
            $tools->disableList();
            $tools->append(new Backforwad());
        });

        $lesson_id = request()->get('lesson_id');
        if ($lesson_id) {
            $lesson = Lesson::findOrFail($lesson_id);
            $form->select('lesson_id', __('Lesson id'))
                ->default($lesson->getAttribute('id'))
                ->options(Lesson::pluck('name', 'id'))
                ->readOnly();
        }

        $form->select('catalog_id', __('Catalog id'))
            ->options(LessonCatalog::where('lesson_id', $lesson_id)->pluck('name', 'id'))
            ->required();
        $form->text('name', __('Name'))
            ->rules('required|max:190');
        $form->date('broadcast_day', __('Broadcast day'))->default(date('Y-m-d H:i:s'));
        $form->time('broadcast_start_at', __('Broadcast start at'))->default(date('Y-m-d H:i:s'));
        $form->time('broadcast_ent_at', __('Broadcast ent at'))->default(date('Y-m-d H:i:s'));
        // $form->text('stream_url', __('Stream url'));
        $form->file('play_back_url', __('Play back url'))
            ->options([
                'initialPreviewConfig' => [['filetype' => 'video/mp4']],
                'initialPreviewFileType' => 'video',
            ])
            ->removable()
            ->help('小节录播视频');
        $form->switch('is_streamed', __('Is streamed'))
            ->states(Presuppose::ON_OFF_STATES);

        return $form;
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     * @author marhone
     */
    public function remove(Request $request)
    {
        $id = $request->post('id');
        $type = $request->post('type');

        if ($type == 'catalog') {
            /** @var LessonCatalog $item */
            $item = LessonCatalog::findOrFail($id);
            if ($item->chapters()->exists()) {
                return [
                    'status' => false,
                    'message' => '课程目录不为空, 不能删除'
                ];
            }
            $item->delete();
            return [
                'status' => true,
                'message' => '课程目录已删除'
            ];
        } else {
            /** @var LessonChapter $item */
            $item = LessonChapter::findOrFail($id);
            $item->delete();
            return [
                'status' => true,
                'message' => '课程小节已删除'
            ];
        }
    }

    /**
     * 生成推流地址
     * @param LessonChapter $lessonChapter
     * @param QiniuStreamService $streamService
     * @return \Illuminate\Http\JsonResponse
     * @author marhone
     */
    public function publishStream(LessonChapter $lessonChapter, QiniuStreamService $streamService)
    {
        $isStreamLesson = $lessonChapter->lesson->is_streamed ?? false;
        if (!$isStreamLesson || !$lessonChapter->getAttribute('is_streamed')) {
            return response()->json([
                'status' => false,
                'message' => '非直播类型课程'
            ]);
        }

        $streamKey = $lessonChapter->getAttribute('stream_key');
        if (!$streamKey) {
            $streamKey = $streamService->randStreamKeyById("chapter{$lessonChapter->getAttribute('id')}");
            $lessonChapter->setAttribute('stream_key', $streamKey);
        }

        // @todo: !!!notice!!!
        $rtmpPublishUrl = $streamService->streamPublishUrl($streamKey);
        $snapshotImageUrl = $streamService->snapshotImageUrl($streamKey);
        $playUrls = $streamService->streamPlayUrls($streamKey);

        $lessonChapter->setAttribute('rtmp_publish_url', $rtmpPublishUrl);
        $lessonChapter->setAttribute('stream_play_urls', $playUrls);
        $lessonChapter->setAttribute('stream_snapshot_image', $snapshotImageUrl);

        $lessonChapter->save();

        return response()->json([
            'status' => true,
            'message' => '推流地址发布成功'
        ]);
    }
}
