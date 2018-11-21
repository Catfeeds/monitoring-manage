@extends('admin::layouts.main')

@section('content')
    @include('admin::search.notics-notics')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">作业列表</h3>
                    <div class="btn-group pull-right">
                        <a href="{{ route('admin::homeworks.create') }}" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;新增
                        </a>
                    </div>
                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::homeworks.index')])
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>作业标题</th>
                            <th>所属班级</th>
                            <th>发布人</th>
                            <th>创建时间</th>
                            <th>截止时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($homeworks as $homework)
                            <tr>
                                <td>{{ $homework->title }}</td>
                                <td>{{ $homework->collective->name }}</td>
                                <td>{{ $homework->adminUser->name }}</td>
                                <td>{{ $homework->created_at }}</td>
                                <td>{{ $homework->end_at }}</td>
                                <td>
                                    <a href="{{ route('admin::homeworks.edit', $homework->id) }}" style="padding:3px 6px;" class="btn btn-info btn-sm" role="button">
                                        <i class="fa fa-edit"></i> 编辑
                                    </a>&nbsp;
                                    <a href="javascript:void(0);" data-id="{{ $homework->id }}" style="padding:3px 6px;" class="btn btn-danger btn-sm grid-row-delete" role="button">
                                        <i class="fa fa-trash"></i> 删除
                                    </a>&nbsp;
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $homeworks->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::homeworks.index')])
    <script type="text/javascript">
        $("#filter-modal .submit").click(function () {
            $("#filter-modal").modal('toggle');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });
    </script>
@endsection