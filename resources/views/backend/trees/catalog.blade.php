    <div class="box">

    <div class="box-header">

        <div class="btn-group">
            <a class="btn btn-primary btn-sm {{ $id }}-tree-tools" data-action="expand" title="{{ trans('admin.expand') }}">
                <i class="fa fa-plus-square-o"></i>&nbsp;{{ trans('admin.expand') }}
            </a>
            <a class="btn btn-primary btn-sm {{ $id }}-tree-tools" data-action="collapse" title="{{ trans('admin.collapse') }}">
                <i class="fa fa-minus-square-o"></i>&nbsp;{{ trans('admin.collapse') }}
            </a>
        </div>

        @if($useSave)
        <div class="btn-group">
            <a class="btn btn-info btn-sm catalog-save" title="{{ trans('admin.save') }}"><i class="fa fa-save"></i><span class="hidden-xs">&nbsp;{{ trans('admin.save') }}</span></a>
        </div>
        @endif

        @if($useRefresh)
        <div class="btn-group">
            <a class="btn btn-warning btn-sm {{ $id }}-refresh" title="{{ trans('admin.refresh') }}"><i class="fa fa-refresh"></i><span class="hidden-xs">&nbsp;{{ trans('admin.refresh') }}</span></a>
        </div>
        @endif

        <div class="btn-group">
            {!! $tools !!}
        </div>

        @if($useCreate)
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#catalogModal"><i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;目录 </span></button>
        </div>
        @endif

    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
        <div class="dd" id="{{ 'catalogTree' }}">
            <ol class="dd-list">
                @each($branchView, $items, 'branch')
            </ol>
        </div>
    </div>
    <!-- /.box-body -->
</div>

<script>
    $(function () {
        $('#catalogTree').nestable();
        $('.catalog-save').click(function () {
            var serialize = $('#catalogTree').nestable('serialize');
            $.post('{{ admin_url('lesson-catalog-orders') }}', {
                    _token: LA.token,
                    _order: JSON.stringify(serialize)
                },
                function(data){
                    $.pjax.reload('#pjax-container');
                    toastr.success('顺序保存成功');
                });
        });
    });
</script>