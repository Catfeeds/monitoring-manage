@extends('admin::layouts.main')

@section('content')

    @include('admin::search.auths-auths')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">申请列表</h3>
                    <div class="btn-group pull-right">

                    </div>
                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::parent_auth.index')])
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            {{--<th>申请人</th>--}}
                            <th>班级</th>
                            <th>学生姓名</th>
                            <th>审核人</th>
                            <th>申请状态</th>
                            <th>申请时间</th>
                            <th style="width: 10%">操作</th>
                        </tr>
                        @inject('authPresenter', "App\Admin\Presenters\AuthPresenter")
                        @foreach($auths as $auth)
                            <tr>
{{--                                <td><img src ="{{$auth->user->avatar}}" height="50" width="50" class="img-circle" /> <b>{{$auth->user->name}}</b></td>--}}
                                <td>{{$auth->collective->name}}</td>
                                <td>{{$auth->student_name}}</td>
                                <td>{!! $authPresenter->operator($auth) !!}</td>
                                <td>{!! $authPresenter->status($auth) !!}</td>
                                <td>{{$auth->created_at}}</td>
                                <td>
                                    <a class="btn btn-info btn-sm show-detail" data-info="{{ $auth }}" data-toggle="modal" data-target="#detail-modal" role="button">
                                        <i class="fa fa-edit"></i>查看
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $auths->links('admin::widgets.pagination') }}
                </div>


                <div class="modal fade" id="detail-modal" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">详情</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="target_id">
                                <form class="form-horizontal">
                                    <div>

                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li role="presentation" class="active"><a href="#student" aria-controls="parent" role="tab" data-toggle="tab">学生信息</a></li>
                                            <li role="presentation"><a href="#schoolNum" aria-controls="profile" role="tab" data-toggle="tab">学号信息</a></li>
                                        </ul>

                                        <!-- Tab panes -->
                                        <div class="tab-content">
                                            <!-- 填写的学生信息 -->
                                            <div role="tabpanel" class="tab-pane active" id="student" style="padding: 15px;">

                                                <div class="form-group">
                                                    <small style="color: red">(*以下为用户申请时填写的信息，请核对)</small>
                                                </div>
                                                <div class="form-group">
                                                    <label for="student_num" class="col-sm-2 control-label">学号</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control student_num" disabled="true" id="student_num" >
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="student_name" class="col-sm-2 control-label">姓名</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" disabled="true" id="student_name" >
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="student_class" class="col-sm-2 control-label">班级</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" disabled="true" id="student_class" >
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- 学号对应的信息 -->
                                            <div role="tabpanel" class="tab-pane" id="schoolNum" style="padding: 15px;">
                                                <div class="form-group">
                                                    <small style="color: red">(*以下为该学号在系统中对应的信息)</small>
                                                </div>
                                                <div class="form-group">
                                                    <label  class="col-sm-2 control-label">学号</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control student_num" disabled="true"  >
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="schoolNum_name" class="col-sm-2 control-label">姓名</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" disabled="true" id="schoolNum_name" >
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="schoolNum_class" class="col-sm-2 control-label">班级</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" disabled="true" id="schoolNum_class" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div>

                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li role="presentation" class="active"><a href="#parent" aria-controls="parent" role="tab" data-toggle="tab">家长信息</a></li>
                                            <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">用户信息</a></li>
                                         </ul>

                                        <!-- Tab panes -->
                                        <div class="tab-content">
                                            <!-- 家长信息 -->
                                            <div role="tabpanel" class="tab-pane active" id="parent" style="padding: 15px;">

                                                <div class="form-group">
                                                    <small style="color: red">(*以下为用户申请时填写的信息，请核对)</small>
                                                </div>
                                                <div class="form-group">
                                                    <label for="parent_name" class="col-sm-2 control-label">姓名</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" disabled="true" id="parent_name" >
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="parent_phone" class="col-sm-2 control-label">联系方式</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" disabled="true" id="parent_phone" >
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="parent_relation" class="col-sm-2 control-label">关系</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" disabled="true" id="parent_relation" >
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- 用户信息 -->
                                            <div role="tabpanel" class="tab-pane" id="profile" style="padding: 15px;">
                                                <div class="form-group">
                                                    <small style="color: red">(*以下为用户注册信息)</small>
                                                </div>
                                                <div class="form-group">
                                                    <label for="user_name" class="col-sm-2 control-label">姓名</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" disabled="true" id="user_name" >
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="user_phone" class="col-sm-2 control-label">联系方式</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" disabled="true" id="user_phone" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="refuse-div" style="border:1px solid #f4f4f4;padding: 15px;margin-bottom: 5px;display: none">

                                        <div class="form-group" style="padding-left: 15px">
                                            <b>拒绝原因 </b><small style="color: red"> (请输入拒绝原因)</small>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-offset-1 col-sm-10">
                                                <textarea  class="form-control"  id="refuse-reason" rows="4" style="outline:none;resize:none;"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <div class="normal-btn-div">
                                    <input type="button" class="btn btn-danger" id="refuse-btn" value="拒绝">
                                    <input type="button" class="btn btn-primary" id="agree-btn" value="同意">
                                    <input type="button" class="btn btn-warning pull-left"  data-dismiss="modal" value="取消">
                                </div>
                                <div class="refuse-div" style="display: none">
                                    <input type="button" class="btn btn-danger" id="sure-refuse-btn" value="确认拒绝">
                                    <input type="button" class="btn btn-warning" id="cancel-refuse-btn" value="取消">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div >
        {!! QrCode::size(200)->generate('http://www.baidu.com'); !!}
        <p>扫描我返回到原始页。</p>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-aways', ['url' => route('admin::parent_auth.index')])
    <script>

        //日期范围
        layui.use('laydate', function(){
            var laydate = layui.laydate;

            lay('.time-select').each(function(){
                laydate.render({
                    elem: this
                    ,trigger: 'click'
                });
            });
        });

        $(".show-detail").click(function () {

            var auth_obj = $(this).data('info');
            $('#target_id').val(auth_obj.id);

            $('.student_num').val(auth_obj.student_num);
            $('#student_name').val(auth_obj.student_name);
            $('#student_class').val(auth_obj.collective.name);
            $('#schoolNum_name').val(auth_obj.schoolNumInfo.name);
            $('#schoolNum_class').val(auth_obj.schoolNumInfo.collective.name);


            $('#parent_name').val(auth_obj.info.parent_name);
            $('#parent_phone').val(auth_obj.info.parent_phone);
            $('#parent_relation').val(auth_obj.info.relation);

            $('#user_name').val(auth_obj.user.name);
            $('#user_phone').val(auth_obj.user.phone);



            var status = auth_obj.status;
            if (status != 1){
                $('.modal-footer').css('display','none');
            }
            if (status == 3){
                $('.refuse-div').css('display','block');
                $('#refuse-reason').val(auth_obj.refusal_reason);
            }
        });


        ///拒绝按钮
        $('#refuse-btn').click(function() {
            $('.refuse-div').css('display','block');
            $('.normal-btn-div').css('display','none');
        });

        ///取消拒绝
        $('#cancel-refuse-btn').click(function () {
            $('.refuse-div').css('display','none');
            $('.normal-btn-div').css('display','block');
        });

        ///确认拒绝按钮
        $('#sure-refuse-btn').click(function() {

            var id = $("#target_id").val();
            var reason = $('#refuse-reason').val();
            if (!reason){
                swal({
                    title: '请填写拒绝原因',
                    type: 'warning',
                    confirmButtonText: '确定'
                })
            }else {
                swal({
                        title: "确认拒绝?",
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
                            url: 'parent_auth/' + id +'/refuse/',
                            data: {
                                _token:LA.token,
                                reason:reason,
                            },
                            success: function (data) {
                                $.pjax.reload('#pjax-container');
                                $("#filter-modal").modal('toggle');
                                $('body').removeClass('modal-open');
                                $('.modal-backdrop').remove();
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
            }

        });

        ///同意按钮
        $('#agree-btn').click(function() {

            var id = $("#target_id").val();
            swal({
                    title: "确认同意?",
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
                        url: 'parent_auth/' + id +'/agree/',
                        data: {
                            _token:LA.token,
                        },
                        success: function (data) {
                            $.pjax.reload('#pjax-container');
                            $("#filter-modal").modal('toggle');
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
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


        $("#filter-modal .submit").click(function () {
            $("#filter-modal").modal('toggle');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });

    </script>
@endsection