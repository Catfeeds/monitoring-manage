@extends('admin::layouts.main')

@section('content')
    @include('admin::search.recipes-recipes')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">学生食谱</h3>

                    <div class="btn-group pull-right">
                        <a href="{{ route('admin::recipes.create') }}" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;新增
                        </a>
                    </div>
                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::recipes.index')])
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>开始日期</th>
                            <th>操作</th>
                        </tr>
                        @foreach($recipes as $recipe)
                            <tr>
                                <td>{{ $recipe->id }}</td>
                                <td>{{ $recipe->begin_start }}</td>
                                <td>
                                    <a href="{{ route('admin::recipes.edit',$recipe->id) }}" style="padding:3px 6px;" class="btn btn-info btn-sm" role="button">
                                        <i class="fa fa-edit"></i> 编辑
                                    </a>
                                    <a href="javascript:void(0);" data-id="{{ $recipe->id }}" style="padding:3px 6px;" class="btn btn-danger btn-sm grid-row-delete" role="button">
                                        <i class="fa fa-trash"></i> 删除
                                    </a>&nbsp;
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $recipes->appends(request()->all())->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::recipes.index')])
    <script type="text/javascript">
        $('#start').datetimepicker({
            format: 'YYYY-MM-DD',
            locale: moment.locale('zh-cn')
        });

        $('#end').datetimepicker({
            format: 'YYYY-MM-DD',
            locale: moment.locale('zh-cn')
        });


        $("#filter-modal .submit").click(function () {
            $("#filter-modal").modal('toggle');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });
    </script>
@endsection