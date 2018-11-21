@extends('admin::layouts.main')

@section('content')

    @include('admin::search.feedbacks-feedbacks')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">反馈意见列表</h3>

                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::feedbacks.index')])

                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>反馈人姓名</th>
                            <th>查看状态</th>
                            <th>反馈时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($feedbacks as $feedback)
                            <tr>
                                <td>{{ $feedback->id }}</td>
                                <td>{{ $feedback->name }}</td>
                                <td><span class="badge bg-red">@if($feedback->state ==0) 未查看@elseif($feedback->state==1)已查看 @endif</span></td>
                                <td>{{$feedback->created_at}}</td>
                                <td>
                                    <a href="{{ route('admin::feedbacks.show', $feedback->id) }}" class="grid-row-show">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    <a href="javascript:void(0);" data-id="{{ $feedback->id }}" class="grid-row-delete">
                                        <i class="fa fa-trash"></i>
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{$feedbacks->appends($data)->links('admin::widgets.pagination')}}
                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::feedbacks.index')])
@endsection
