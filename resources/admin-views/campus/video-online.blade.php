@extends('admin::layouts.main')

@section('content')
    @include('admin::search.videoOnline-videoOnline')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">视频在线</h3>

                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::videoOnline.index')])
                    <div class="btn-group pull-right" style="margin-right: 10px">
                        <a href="{{ route('admin::videoOnline.export') }}" target="_blank" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;数据导出
                        </a>
                    </div>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>学生姓名</th>
                            <th>年级</th>
                            <th>班级</th>
                            <th>家长详情</th>
                        </tr>
                        @foreach($students as $student)
                            <tr>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->grade->name }}</td>
                                <td>{{ $student->collective->name }}</td>
                                <td>
                                    <a class="btn btn-xs btn-default grid-expand collapsed" data-inserted="0" data-key="{{ $student->id }}" data-toggle="collapse" data-target="#grid-collapse-{{ $student->id }}" aria-expanded="false">
                                        <i class="fa fa-caret-right"></i> 详情
                                    </a>
                                    <template class="grid-expand-{{ $student->id }}">
                                        <div id="grid-collapse-{{ $student->id }}" class="collapse">
                                            <div class="box box-primary box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">家长详情</h3>
                                                    <div class="box-tools pull-right">
                                                    </div>
                                                </div>
                                                <div class="box-body" style="display: block;">
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th>家长姓名</th>
                                                            <th>联系方式</th>
                                                            <th>家长来源</th>
                                                            <th>视频在线状态</th>
                                                            <th>剩余到期天数</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($student->parents as $parent)
                                                            <tr>
                                                                <td>{{ $parent->name }}</td>
                                                                <td>{{ $parent->phone }}</td>
                                                                <td>{{ $parent->way }}</td>
                                                                <td>{{ $status=strtotime($parent->collectives()->where('class_id',$student->collective->id)->first()->pivot->expire_at)>strtotime(date('Y-m-d H:i:s'))?'已开通':'未开通' }}</td>
                                                                <td>@if($status == '未开通'){{0}}@else{{ ceil((strtotime($parent->collectives()->where('class_id',$student->collective->id)->first()->pivot->expire_at)-strtotime(date('Y-m-d H:i:s')))/86400) }}@endif</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $students->appends(request()->all())->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script type="text/javascript">
        $('.grid-expand').on('click', function () {
            if ($(this).data('inserted') == '0') {
                var key = $(this).data('key');
                var row = $(this).closest('tr');
                var html = $('template.grid-expand-'+key).html();

                row.after("<tr><td colspan='"+row.find('td').length+"' style='padding:0 !important; border:0px;'>"+html+"</td></tr>");

                $(this).data('inserted', 1);
            }

            $("i", this).toggleClass("fa-caret-right fa-caret-down");
        });

        $("#filter-modal .submit").click(function () {
            $("#filter-modal").modal('toggle');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });
    </script>
@endsection
