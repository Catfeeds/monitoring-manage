@extends('admin::layouts.main')

@section('content')

    {{--@include('admin::search.shops-shops')--}}

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">学校列表</h3>
                    <div class="btn-group pull-right">
                        <a href="{{ route('admin::schools.create') }}" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;新增
                        </a>
                    </div>
                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::schools.index')])
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>学校名</th>
                            <th>状态</th>
                            <th>学校注册时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($schools as $school)
                            <tr>
                                <td>{{ $school->id }}</td>
                                <td> <a><button data-id="{{ $school->id }}" type="button" class="btn btn-warning school-btn">
                                        {{$school->name}}

                                        </button></a>
                                </td>
                                <td> <a href="javascript:void(0);" data-id="{{ $school->id }}" class="grid-row-change" value="{{$school->id}}"><button type="button" class="btn btn-info">
                                        <i class="fa fa-bank"></i>
                                    @if($school->state)关闭@else开启@endif
                                        </button>
                                    </a>
                                </td>
                                <td>{{ $school->created_at }}</td>
                                <td>
                                    <a href="{{ route('admin::schools.edit', $school->id) }}" role="button" class="btn btn-info btn-sm">
                                        <i class="fa fa-edit"></i>编辑
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $schools->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-change', ['url' => route('admin::schools.index')])
    <script>
            $(".school-btn").unbind('click').click(function () {
                var id = $(this).data('id');
                $.ajax({
                    method: 'get',
                    url: '{{ route('admin::schools.index') }}' + '/' + id,
                    data: {
                        _token:LA.token
                    },
                    success: function (data) {
                       // $.pjax.reload('#pjax-container');
                       //  $.pjax.reload('#pjax-header');
                        location.reload();
                    }
                });
            })

    </script>
@endsection