@extends('admin::layouts.main')

@section('content')

    @include('admin::search.teachers-teachers')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">园丁账号审核列表-已审核</h3>
                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::teacherApplies.finish')])
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
                                    <a href="javascript:void(0);" data-id="{{ $apply->id }}" style="padding:3px 6px;" class="btn btn-danger btn-sm grid-row-delete" role="button">
                                        <i class="fa fa-trash"></i> 删除
                                    </a>&nbsp;
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
    @include('admin::js.grid-row-delete', ['url' => url("/admin/teacherApplies")])
@endsection