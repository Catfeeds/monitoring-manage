@extends('admin::layouts.main')

@section('content')

    @include('admin::search.teachers-teachers')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">园丁账号审核列表-待审核</h3>
                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::teachers.index')])
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>头像</th>
                            <th>姓名</th>
                            <th>手机号</th>
                            <th>性别</th>
                            <th>选择班级</th>
                            <th>申请时间</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        @foreach($teacherApplies as $apply)
                            <tr>
                                <td><img src ="{{ Storage::url($apply->avatar) }}" height="50" width="50" class="img-circle" alt=""/></td>
                                <th>{{ $apply->name}}</th>
                                <td>{{ $apply->tel }}</td>
                                <td>@if($apply->sex == 1)男@else女@endif</td>
                                <td>{{ $apply->collective->name }}</td>
                                <td>{{ $apply->created_at }}</td>
                                <td>{{ $apply->status }}</td>
                                <td>
                                    <a href="javascript:void(0);" data-action="{{ route('admin::teacherApplies.agreed',$apply->id )}}" style="padding:3px 6px;" class="btn btn-success btn-sm grid-row-agree" role="button" title="同意申请">
                                        <i class="fa fa-check-square"></i> 同意
                                    </a>
                                    <a href="javascript:void(0);" data-action="{{ route('admin::teacherApplies.refused',$apply->id) }}" style="padding:3px 6px;" class="btn bg-orange btn-sm grid-row-refuse" role="button" title="拒绝申请">
                                        <i class="fa fa-times-circle"></i> 拒绝
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $teacherApplies->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-away', ['url' => route('admin::teachers.index')])
    <script>
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
    </script>
@endsection