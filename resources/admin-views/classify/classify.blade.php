@extends('admin::layouts.main')

@section('content')

    {{--@include('admin::search.shops-shops')--}}

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">分类列表</h3>
                    <div class="btn-group pull-right">
                        <a href="{{ route('admin::classify.create') }}" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;新增
                        </a>
                    </div>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>分类名</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($classifies as $classify)
                            <tr>
                                <td>{{ $classify->name }}</td>
                                <td>{{$classify->created_at}}</td>

                                <td>
                                    <a href="{{ route('admin::classify.edit', $classify->id) }}" role="button" class="btn btn-info btn-sm">
                                        <i class="fa fa-edit"></i>编辑
                                    </a>
                                    <a href="javascript:void(0);" data-id="{{ $classify->id }}" class="btn btn-danger btn-sm grid-row-delete">
                                        <i class="fa fa-trash"></i>删除
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $classifies->links('admin::widgets.pagination') }}
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::classify.index')])
@endsection