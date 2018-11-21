@extends('admin::layouts.main')

@section('content')

    @include('admin::search.cameras-cameras')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">摄像头列表</h3>
                    <div class="btn-group pull-right">
                        <a href="{{ route('admin::cameras.create') }}" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;新增
                        </a>
                    </div>
                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::cameras.index')])
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>uid</th>
                            <th>班级</th>
                            <th>摄像头名称</th>
                            <th>摄像头添加时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($cameras as $camera)
                            <tr>
                                <td>{{ $camera->id }}</td>
                                <td>{{$camera->uid}}</td>
                                <td>{{$camera->collective->grade->name.$camera->collective->name}}</td>
                                <td>{{ $camera->area }}</td>
                                <td>{{ $camera->created_at }}</td>
                                <td>
                                    <a href="{{ route('admin::cameras.edit', $camera->id) }}" role="button" class="btn btn-info btn-sm" >
                                        <i class="fa fa-edit"></i>编辑
                                    </a>
                                    <a href="javascript:void(0);" data-id="{{ $camera->id }}" class="btn btn-danger btn-sm grid-row-delete">
                                        <i class="fa fa-trash"></i>删除
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $cameras->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::cameras.index')])
@endsection