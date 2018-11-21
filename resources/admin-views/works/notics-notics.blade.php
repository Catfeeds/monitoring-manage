@extends('admin::layouts.main')

@section('content')
@include('admin::search.notics-notics')

<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">通知列表</h3>
                <div class="btn-group pull-right">
                    <a href="{{ route('admin::notics.create') }}" class="btn btn-sm btn-success">
                        <i class="fa fa-save"></i>&nbsp;&nbsp;新增
                    </a>
                </div>
                @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::notics.index')])
            </div>

            <div class="box-body table-responsive no-padding">
                <table class="table">
                    <tbody>
                    <tr>
                        <th>通知标题</th>
                        <th>发送对象</th>
                        <th>发布人</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                    @foreach($notics as $notic)
                    <tr>
                        <td>{{ $notic->title }}</td>
                        <td>{{ $notic->scope }}</td>
                        <td>{{ $notic->adminUser->name }}</td>
                        <td>{{ $notic->created_at }}</td>
                        <td>
                            <a href="{{ route('admin::notics.edit', $notic->id) }}" style="padding:3px 6px;" class="btn btn-info btn-sm" role="button">
                                <i class="fa fa-edit"></i> 编辑
                            </a>&nbsp;
                            <a href="javascript:void(0);" data-id="{{ $notic->id }}" style="padding:3px 6px;" class="btn btn-danger btn-sm grid-row-delete" role="button">
                                <i class="fa fa-trash"></i> 删除
                            </a>&nbsp;
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="box-footer">
                {{ $notics->appends(request()->all())->links('admin::widgets.pagination') }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::notics.index')])
<script type="text/javascript">
    $("#filter-modal .submit").click(function () {
        $("#filter-modal").modal('toggle');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    });
</script>
@endsection