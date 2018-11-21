@extends('admin::layouts.main')

@section('content')

    @include('admin::search.students-students')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">学生列表</h3>
                    <div class="btn-group pull-right">
                        <a href="{{ route('admin::students.create') }}" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;新增
                        </a>

                        <a href="{{ route('admin::student.export') }}" target="_blank" class="btn btn-sm btn-yahoo">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;数据导出
                        </a>
                    </div>
                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::students.index')])
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>学生头像</th>
                            <th>学生姓名</th>
                            <th>班级</th>
                            <th>性别</th>
                            <th>学生添加时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($students as $student)
                            <tr>
                                <td><img src ="{{$student->avatar}}" height="50" width="50" class="img-circle" /></td>
                                <th>{{$student->name}}</th>
                                <td>{{$student->collective->grade->name.$student->collective->name}}</td>
                                <td>{{$student->sex}}</td>
                                <td>{{$student->created_at}}</td>
                                <td>
                                    <a href="{{ route('admin::students.edit', $student->id) }}" class="btn btn-info btn-sm" role="button">
                                        <i class="fa fa-edit"></i>编辑
                                    </a>
                                    <a href="javascript:void(0);" data-id="{{ $student->id }}" class="grid-row-aways btn btn-danger btn-sm" role="button">
                                        <i class="fa fa-ban"></i>离校/毕业
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $students->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-aways', ['url' => route('admin::students.index')])
@endsection