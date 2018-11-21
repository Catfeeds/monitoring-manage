@extends('admin::layouts.main')

@section('content')
    @include('admin::search.relaxApplies-relaxApplies')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">请假列表</h3>

                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::relaxApplies.applying')])
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>家长名称</th>
                            <th>请假学生信息</th>
                            <th>请假原因</th>
                            <th>请假图片</th>
                            <th>开始时间</th>
                            <th>结束时间</th>
                            <th>大概天数</th>
                            <th>审核教师</th>
                            <th>申请状态</th>
                            <th>申请时间</th>
                            <th>操作</th>
                        </tr>
                        @inject('relaxPresenter', "App\Admin\Presenters\RelaxPresenter")
                        @foreach($relaxApplies as $relaxApply)
                            <tr>
                                <td>{{ $relaxApply->parent->name }}</td>
                                <td>
                                    <a class="btn btn-xs btn-default grid-expand collapsed" data-inserted="0" data-key="{{ $relaxApply->id }}" data-toggle="collapse" data-target="#grid-collapse-{{ $relaxApply->id }}" aria-expanded="false">
                                        <i class="fa fa-caret-right"></i> 详情
                                    </a>
                                    <template class="grid-expand-{{ $relaxApply->id }}">
                                        <div id="grid-collapse-{{ $relaxApply->id }}" class="collapse">
                                            <div class="box box-primary box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">学生详情</h3>
                                                    <div class="box-tools pull-right">
                                                    </div>
                                                </div>
                                                <div class="box-body" style="display: block;">
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th>学生头像</th>
                                                            <th>学生姓名</th>
                                                            <th>性别</th>
                                                            <th>所在年段</th>
                                                            <th>所在班级</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><img class="img-circle" width="50" height="50" src="{{ $relaxApply->student->avatar }}"></td>
                                                                <td>{{ $relaxApply->student->name }}</td>
                                                                <td>{{ $relaxApply->student->sex }}</td>
                                                                <td>{{ $relaxApply->student->grade->name }}</td>
                                                                <td>{{ $relaxApply->student->collective->name }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </td>
                                <td><a href="javascript:void(0);" class="showReason" data-reason="{{ $relaxApply->reason }}" data-toggle="modal" data-target="#myModal">
                                        <i class="fa fa-edit"></i> 查看
                                    </a></td>
                                <td>
                                    <a class="btn btn-xs btn-default grid-expand-image collapsed" data-inserted="0" data-key="{{ $relaxApply->id }}" data-toggle="collapse" data-target="#grid-collapse-image-{{ $relaxApply->id }}" aria-expanded="false">
                                        <i class="fa fa-caret-right"></i> 查看
                                    </a>
                                    <template class="grid-expand-image-{{ $relaxApply->id }}">
                                        <div id="grid-collapse-image-{{ $relaxApply->id }}" class="collapse">
                                            <div class="box box-primary box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">请假图片</h3>
                                                    <div class="box-tools pull-right">
                                                    </div>
                                                </div>
                                                <div class="box-body" style="display: block;">
                                                    @if($relaxApply->covers->count()>0)
                                                        @foreach($relaxApply->covers as $k => $cover)
                                                            <img src="{{ Storage::disk('public')->url($cover->path) }}" width="100" onclick="showImage(this)" class=" rounded img-thumbnail" data-image="{{ Storage::disk('public')->url($cover->path) }}" data-toggle="modal" data-target="#imageModal" />
                                                        @endforeach
                                                    @else
                                                        <span>用户未上传图片</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </td>
                                <td>{{ $relaxApply->begin }}</td>
                                <td>{{ $relaxApply->end }}</td>
                                <td>{{ $relaxApply->date_num }}天</td>
                                <td>{{ $relaxApply->teacher->name }}</td>
                                <td>{!! $relaxPresenter->status($relaxApply)!!}</td>
                                <td>{{ $relaxApply->created_at }}</td>
                                <td>
                                    <a href="javascript:void(0);" data-action="{{ route('admin::relaxApplies.agreed',$relaxApply->id )}}" style="padding:3px 6px;" class="btn btn-success btn-sm grid-row-agree" role="button" title="同意申请">
                                        <i class="fa fa-check-square"></i> 同意
                                    </a>
                                    <a href="javascript:void(0);" data-action="{{ route('admin::relaxApplies.refused',$relaxApply->id) }}" style="padding:3px 6px;" class="btn bg-orange btn-sm grid-row-refuse" role="button" title="拒绝申请">
                                        <i class="fa fa-times-circle"></i> 拒绝
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="modal fade text-center" id="imageModal">
                        <div class="modal-dialog modal-lg" style="display: inline-block;width: auto;" >
                            <div class="modal-content">

                                <!-- 模态框头部 -->
                                <div class="modal-header">
                                    <h4 class="modal-title text-left">请假图片</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- 模态框主体 -->
                                <div class="modal-body" id="displayImage">

                                </div>

                                <!-- 模态框底部 -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-warning" data-dismiss="modal">关闭</button>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="modal fade" id="myModal">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- 模态框头部 -->
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span></button>
                                    <h4 class="modal-title text-left">请假原因</h4>
                                </div>

                                <!-- 模态框主体 -->
                                <div class="modal-body" id="displayReason">

                                </div>

                                <!-- 模态框底部 -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-warning" data-dismiss="modal">关闭</button>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {{ $relaxApplies->appends(request()->all())->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('.grid-expand').on('click', function () {
            if ($(this).data('inserted') == '0') {
                var key = $(this).data('key');
                var row = $(this).closest('tr');
                var html = $('template.grid-expand-'+key).html();

                row.after("<tr><td colspan='"+row.find('td').length+"' style='padding:0 !important; border:0px;'>"+html+"</td></tr>");

                $(this).data('inserted', 1);
            }

            $("i", this).toggleClass("fa-caret-right fa-caret-down");
        });

        $('.grid-expand-image').on('click', function () {
            if ($(this).data('inserted') == '0') {
                var key = $(this).data('key');
                var row = $(this).closest('tr');
                var html = $('template.grid-expand-image-'+key).html();

                row.after("<tr><td colspan='"+row.find('td').length+"' style='padding:0 !important; border:0px;'>"+html+"</td></tr>");

                $(this).data('inserted', 1);
            }

            $("i", this).toggleClass("fa-caret-right fa-caret-down");
        });

        $('#start').datetimepicker({
            format: 'YYYY-MM-DD',
            locale: moment.locale('zh-cn')
        });

        $('#end').datetimepicker({
            format: 'YYYY-MM-DD',
            locale: moment.locale('zh-cn')
        });

        $("#filter-modal .submit").click(function () {
            $("#filter-modal").modal('toggle');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });

        $('.showReason').on('click',function () {
            var reason = $(this).data('reason');
            $('#displayReason').html(reason);
        });

        $('.grid-row-agree').unbind('click').click(function() {

            var action = $(this).data('action');

            swal({
                    title: "同意申请?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确认",
                    closeOnConfirm: false,
                    cancelButtonText: "取消"
                },
                function(){
                    $.ajax({
                        method: 'put',
                        url: action,
                        data: {
                            _token:LA.token
                        },
                        success: function (data) {
                            $.pjax.reload('#pjax-container');

                            if (typeof data === 'object') {
                                if (data.status) {
                                    swal(data.message, '', 'success');
                                } else {
                                    swal(data.message, '', 'error');
                                }
                            }
                        }
                    });
                });
        });

        $('.grid-row-refuse').unbind('click').click(function() {

            var action = $(this).data('action');

            swal({
                    title: "拒绝申请?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确认",
                    closeOnConfirm: false,
                    cancelButtonText: "取消"
                },
                function () {
                    $.ajax({
                        method: 'put',
                        url: action,
                        data: {
                            _token: LA.token
                        },
                        success: function (data) {
                            $.pjax.reload('#pjax-container');

                            if (typeof data === 'object') {
                                if (data.status) {
                                    swal(data.message, '', 'success');
                                } else {
                                    swal(data.message, '', 'error');
                                }
                            }
                        }
                    });
                });
        });

        function showImage(obj) {
            var path = $(obj).data('image');
            $('#displayImage').html("<image src='"+ path + "' class='carousel-inner img-responsive img-rounded' />");
        }
    </script>
@endsection