<li class="dd-item" data-id="{{ $branch[$keyName] }}">
    <div class="dd-handle">
        {!! $branchCallback($branch) !!}
        <span class="pull-right dd-nodrag">
            @if (isset($branch['catalog_id']))
                <a href="{{ admin_url('lesson-chapters') }}/{{ $branch[$keyName] }}/edit?lesson_id={{ request()->get('lesson_id') }}"><i class="fa fa-edit"></i></a>
                <a href="#" class="branch-remove" data-type="chapter" data-url="{{ admin_url('branches/remove') }}" data-id="{{ $branch[$keyName] }}"><i class="fa fa-trash"></i></a>
            @else
{{--                <a href="{{ admin_url('lesson-catalogs') }}/{{ $branch[$keyName] }}/edit?lesson_id={{ request()->get('lesson_id') }}"><i class="fa fa-edit"></i></a>--}}
                <a href="#" class="branch-edit" data-id="{{ $branch[$keyName] }}"><i class="fa fa-edit"></i></a>
                <a href="#" class="branch-remove" data-type="catalog" data-url="{{ admin_url('branches/remove') }}" data-id="{{ $branch[$keyName] }}"><i class="fa fa-trash"></i></a>
            @endif
        </span>
    </div>
    @if(isset($branch['children']))
    <ol class="dd-list">
        @foreach($branch['children'] as $branch)
            @include($branchView, $branch)
        @endforeach
    </ol>
    @endif
</li>

<div class="modal fade" id="catalog-edit-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="catalog-modal-title">编辑</h4>
            </div>
            <div class="modal-body load_modal" id="catalog-modal-content"></div>
        </div>
    </div>
</div>

<script>

$('.branch-edit').click(function () {
    var that = this;
    $.get('{{ admin_url('branches/edit') }}/' + $(that).data('id'), {
    }, function (data) {
        // console.log(data);
        $('#catalog-modal-content').html(data);
        $('#catalog-edit-modal').modal('show');
    });
});

$('.branch-remove').click(function () {
    var that = this;
    swal({
        title: "确认要删除吗?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "确定",
        cancelButtonText: "取消"
    }).then(function (choose) {
        console.log(choose);
        if (choose.value === true) {
            var target = $(that).data('url');
            var id = $(that).data('id');
            var type = $(that).data('type');
            console.log(target);
            $.post(target, {
                _token: LA.token,
                id: id,
                type: type
            },
            function(data){
                console.log(data);
                if (data.status) {
                    toastr.success(data.message);
                    $.pjax.reload('#pjax-container');
                } else {
                    toastr.error(data.message);
                }
            });
        }
    });
});
</script>

