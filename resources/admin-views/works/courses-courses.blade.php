@extends('admin::layouts.main')

@section('content')
    @include('admin::search.courses-courses')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">学生课程</h3>

                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::courses.index')])
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>年级名称</th>
                            <th>班级名称</th>
                            <th>操作</th>
                        </tr>
                        @foreach($collectives as $collective)
                            <tr>
                                <td>{{ $collective->grade->name }}</td>
                                <td>{{ $collective->name }}</td>
                                <td>
                                    <a href="{{ route('admin::courses.setCourse',$collective->id) }}" target="_self" style="padding:3px 6px;" class="btn btn-info btn-sm" role="button">
                                        <i class="fa fa-edit"></i> 设置课程
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $collectives->appends(request()->all())->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(function () {
            var grade_id = "{{ request('grade_id') }}";
            {{--alert({{ request('id') }});--}}
            if(grade_id) {
                $.ajax({
                    method: 'get',
                    url: '{{ route('admin::grades.index') }}' + '/getCollectives/' + grade_id,
                    success: function (data) {
                        if (typeof data === 'object') {
                            if (data.status) {
                                var str = '<option value="">请选择班级</option>';
                                var flag;
                                $.each(data.collectives, function (i, item) {
                                    flag = '';
                                    if(item.id == "{{ request('id') }}") {
                                        flag = 'selected';
                                    }
                                    str += '<option value="' + item.id  + '" '+ flag +'>' + item.name + '</option>';
                                })
                                $('#collective').html(str);
                            }
                        }
                    }
                });
            }

            $("#grade").select2({
                "allowClear": true
            });

            $("#collective").select2({
                "allowClear": true
            });

            $.fn.modal.Constructor.prototype.enforceFocus = function () { }
        });

        $("#filter-modal .submit").click(function () {
            $("#filter-modal").modal('toggle');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });

        $('#grade').on('change',function () {
            var grade_id = $(this).val();
            $.ajax({
                method: 'get',
                url: '{{ route('admin::grades.index') }}' + '/getCollectives/' + grade_id,
                success: function (data) {
                    if (typeof data === 'object') {
                        if (data.status) {
                            var str = '<option value="">请选择班级</option>';
                            // var flag;
                            $.each(data.collectives, function (i, item) {
                                {{--flag = '';--}}
                                {{--if(item.id == "{{ request('grade_id') }}") {--}}
                                    {{--flag = 'selected';--}}
                                {{--}--}}
                                str += '<option value=' + item.id+'>' + item.name + '</option>';
                            })
                            $('#collective').html(str);
                        }
                    }
                }
            });
        });


    </script>
@endsection