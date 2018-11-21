@extends('admin::layouts.main')

@section('content')

    @include('admin::search.students-students')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">离校/毕业学生</h3>

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
                                <td>@if($student->sex == 1)男@else女@endif</td>
                                <td>{{$student->created_at}}</td>
                                <td>

                                    <a href="{{ route('admin::students.reduction',$student->id ) }}" class="btn btn-sm btn-success">
                                        <i class="fa fa-recycle"></i>&nbsp;&nbsp;恢复
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
    @include('admin::js.grid-row-delete', ['url' => route('admin::students.index')])
@endsection