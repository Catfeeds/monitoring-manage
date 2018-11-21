@extends('admin::layouts.main')

@section('content')

    {{--@include('admin::search.shops-shops')--}}

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">学校列表</h3>
                    <div class="btn-group pull-right">
                        <a href="{{ route('admin::user.create') }}" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;新增
                        </a>
                    </div>
                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::user.index')])
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>头像</th>
                            <th>家长名称</th>
                            <th>孩子名称</th>
                            <th>状态</th>
                            <th>等级</th>
                            <th>注册时间</th>
                        </tr>
                        @foreach($users as $user)
                            <tr>
                                <td><img src ="{{ $user->avatar }}" height="50" width="50" class="img-circle" alt=""/></td>
                                <td>{{ $user->name }}</td>
                                <td>
                                        <a class="btn btn-xs btn-default grid-expand collapsed" data-inserted="0" data-key="{{ $user->id }}" data-toggle="collapse" data-target="#grid-collapse-{{ $user->id }}" aria-expanded="false">
                                        <i class="fa fa-caret-right"></i> 详情
                                    </a>
                                    <template class="grid-expand-{{ $user->id }}">
                                        <div id="grid-collapse-{{ $user->id }}" class="collapse">
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
                                                            <th>学生姓名</th>
                                                            <th>学生班级</th>
                                                            <th>关系</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($user->students as $student)
                                                            <tr>
                                                                <td>{{ $student->name }}</td>
                                                                <td>{{ $student->collective->name }}</td>
                                                                <td>{{ $student->pivot->role }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </td>
                                <td>
                                    <a href="javascript:void(0);" data-id="{{ $user->id }}" class="grid-row-change" value="{{$user->id}}">
                                        {{ $user->status }}
                                    </a>
                                </td>
                                <td>{{ $user->grades }}</td>
                                <td>{{ $user->created_at }}</td>
                                {{--<td>--}}
                                    {{--<a href="{{ route('admin::user.edit', $user->id) }}" role="button" class="btn btn-info btn-sm">--}}
                                        {{--<i class="fa fa-edit"></i>查看--}}
                                    {{--</a>--}}

                                {{--</td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{--{{ $user->links('admin::widgets.pagination') }}--}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-change', ['url' => route('admin::user.index')])
    <script>
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

    </script>
@endsection