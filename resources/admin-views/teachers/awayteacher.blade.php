@extends('admin::layouts.main')

@section('content')

    @include('admin::search.teachers-teachers')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">离职教师列表</h3>

                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::teachers.index')])
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>

                            <th>教师头像</th>
                            <th>教师姓名</th>
                            {{--<th>任教班级</th>--}}
                            <th>是否离职</th>
                            <th>是否校长</th>
                            <th>创建时间</th>
                            <th>教师联系方式</th>
                            <th>备注</th>
                            <th>操作</th>
                        </tr>
                        @foreach($teachers as $teacher)
                            <tr>

                                <td><img src ="{{$teacher->avatar}}" height="50" width="50" class="img-circle" alt=""/></td>
                                <th>{{ $teacher->name}}</th>
                                {{--<td>{{$teacher->collective->grade->name.$teacher->collective->name}}</td>--}}
                                <td>@if($teacher->state == 1)否@else是@endif</td>
                                <td>@if($teacher->ishead == 1)否@else是@endif</td>
                                <td>{{$teacher->created_at}}</td>
                                <td>{{$teacher->tel}}</td>
                                <td>{{$teacher->note}}</td>
                                <td>
                                    <a href="{{ route('admin::teachers.reduction',$teacher->id ) }}" class="btn btn-sm btn-success">
                                        <i class="fa fa-recycle"></i>&nbsp;&nbsp;恢复
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $teachers->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::teachers.index')])
@endsection