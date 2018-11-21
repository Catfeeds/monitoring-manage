@extends('admin::layouts.main')

@section('content')

    {{--@include('admin::search.shops-shops')--}}

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">收费列表</h3>
                    <div class="btn-group pull-right">
                        <a href="{{ route('admin::charges.create') }}" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;新增
                        </a>
                    </div>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>

                            <th>收费价格(元)</th>
                            <th>收费周期(天)</th>
                            <th>操作</th>
                        </tr>
                        @foreach($charges as $charge)
                            <tr>

                                <td>{{$charge->money}}</td>

                                <td>{{ $charge->time }}</td>
                                <td>
                                    <a href="{{ route('admin::charges.edit', $charge->id) }}" role="button" class="btn btn-info btn-sm">
                                        <i class="fa fa-edit"></i>编辑
                                    </a>
                                    <a href="javascript:void(0);" data-id="{{ $charge->id }}" class="btn btn-danger btn-sm grid-row-delete">
                                        <i class="fa fa-trash"></i>删除
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $charges->links('admin::widgets.pagination') }}
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::charges.index')])
@endsection