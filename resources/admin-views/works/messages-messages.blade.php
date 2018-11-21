@extends('admin::layouts.main')

@section('content')
    @include('admin::search.messages-messages')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">留言列表</h3>

                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::messages.index')])
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>家长名称</th>
                            <th>学生信息</th>
                            <th>指定教师</th>
                            <th>留言内容</th>
                            <th>留言图片</th>
                            <th>留言时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($messages as $message)
                            <tr>
                                <td>{{ $message->parent->name }}</td>
                                <td>
                                    <a class="btn btn-xs btn-default grid-expand collapsed" data-inserted="0" data-key="{{ $message->id }}" data-toggle="collapse" data-target="#grid-collapse-{{ $message->id }}" aria-expanded="false">
                                        <i class="fa fa-caret-right"></i> 详情
                                    </a>
                                    <template class="grid-expand-{{ $message->id }}">
                                        <div id="grid-collapse-{{ $message->id }}" class="collapse">
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
                                                        @foreach($message->parent->students()->where('school_id',$message->school_id)->get() as $student)
                                                            <tr>
                                                                <td><img class="img-circle" width="50" height="50" src="{{ $student->avatar }}"></td>
                                                                <td>{{ $student->name }}</td>
                                                                <td>{{ $student->sex }}</td>
                                                                <td>{{ $student->grade->name }}</td>
                                                                <td>{{ $student->collective->name }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </td>
                                <td>{{ $message->teacher->name }}</td>
                                <td><a href="javascript:void(0);" class="showMessage" data-content="{{ $message->content }}" data-toggle="modal" data-target="#myModal">
                                        <i class="fa fa-edit"></i> 查看
                                    </a>&nbsp;</td>
                                <td>
                                    <a class="btn btn-xs btn-default grid-expand-image collapsed" data-inserted="0" data-key="{{ $message->id }}" data-toggle="collapse" data-target="#grid-collapse-image-{{ $message->id }}" aria-expanded="false">
                                        <i class="fa fa-caret-right"></i> 查看
                                    </a>
                                    <template class="grid-expand-image-{{ $message->id }}">
                                        <div id="grid-collapse-image-{{ $message->id }}" class="collapse">
                                            <div class="box box-primary box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">留言图片</h3>
                                                    <div class="box-tools pull-right">
                                                    </div>
                                                </div>
                                                <div class="box-body" style="display: block;">
                                                    @if($message->covers->count()>0)
                                                        @foreach($message->covers as $k => $cover)
                                                            <img src="{{ Storage::disk('public')->url($cover->path) }}" width="100" onclick="showImage(this)" class=" rounded img-thumbnail" data-image="{{ Storage::disk('public')->url($cover->path) }}" data-toggle="modal" data-target="#imageModal" />
                                                        @endforeach
                                                    @else
                                                        <span>家长未上传图片</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </td>
                                <td>{{ $message->created_at }}</td>
                                <td>
                                    <a href="javascript:void(0);" data-id="{{ $message->id }}" style="padding:3px 6px;" class="btn btn-danger btn-sm grid-row-delete" role="button">
                                        <i class="fa fa-trash"></i> 删除
                                    </a>&nbsp;
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
                                    <h4 class="modal-title text-left">留言图片</h4>
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
                                    <h4 class="modal-title text-left">家长留言</h4>
                                </div>

                                <!-- 模态框主体 -->
                                <div class="modal-body" id="displayMessage">

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
                    {{ $messages->appends(request()->all())->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::messages.index')])
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

        $('.showMessage').on('click',function () {
            var content = $(this).data('content');
            $('#displayMessage').html(content);
        })

        function showImage(obj) {
            var path = $(obj).data('image');
            $('#displayImage').html("<image src='"+ path + "' class='carousel-inner img-responsive img-rounded' />");
        }
    </script>
@endsection