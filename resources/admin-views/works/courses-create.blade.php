@extends('admin::layouts.main')

@section('content')
    <style>
        td,th{text-align: center;}
        .textarea{resize:none; }
        .center{text-align:center;vertical-align: middle;}
        .hover{background-color:#f0f0f0;}
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">设置课程</h3>
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            <a href="{{ route('admin::courses.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a>
                        </div> <div class="btn-group pull-right" style="margin-right: 10px">
                            <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                        </div>
                    </div>

                </div>
                <div class="box-header with-border">
                    {{--<button type="button" id="prev_week" class="btn btn-success"><i class="fa fa-hand-o-left"></i>&nbsp;上一周</button>--}}
                    {{--<button type="button" id="next_week" class="btn btn-success"><i class="fa fa-hand-o-right"></i>&nbsp;下一周</button>--}}
                    {{--<button type="button" id="current_week" class="btn btn-success"><i class="fa fa-repeat"></i>&nbsp;返回本周</button>--}}
                    {{--<button type="button" id="copy_course" class="btn btn-success"><i class="fa fa-clone"></i>&nbsp;复制上一周</button>--}}
                    <a href="javascript:void(0)" id="export" class="btn bg-orange"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;导出课程</a>
                    <div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" >
                            <i class="fa fa-upload" aria-hidden="true"></i>&nbsp;
                            导入 <span class="caret"></span></button>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <div style="height:30px;position:relative;width:100px;margin-top:5px;">
                                    <a style="margin-left: 20px;">导入</a>
                                    <input style="height:30px; width:100px;overflow: hidden;position:absolute;right:0;top:0;float:left;opacity: 0;filter:alpha(opacity=0);cursor:pointer;" type="file" name="courseData" id="courseData" accept=".xls,.xlsx" onchange="uploadCourses()" />
                                </div>
                            </li>
                            <li><a href="javascript:void(0)" id="downloadCourse" style="color: #428bca;">下载导入模板</a></li>
                        </ul>
                    </div>
                </div>

                <form id="post-form" class="form-horizontal" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group">
                            <div class="col-sm-12 ">
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="border-top:1px solid #ddd;white-space:nowrap;" >
                                        <thead>
                                        <tr>
                                            <input type="hidden" id="current_week1" value="{{ $week[0]['date'] }}" />
                                            <input type="hidden" name="class_id" value="{{ $collective->id }}" />
                                            <input type="hidden" name="begin_start" value="{{ $week[0]['date'] }}" />
                                            <th style="vertical-align: middle;">时间</th>
                                            @foreach($week as $k=>$v)
                                                <th style="vertical-align: middle;"><div>{{ $v['week'] }}</div><div id="week_{{ $k+1 }}">{{ $v['date'] }}</div></th>
                                            @endforeach
                                        </tr>
                                        </thead>
                                        <tr>
                                            <td style="vertical-align: middle;">上午</td>
                                            <td style="vertical-align: middle;"><textarea class="textarea" value="" cols="24" rows="5" name="up_C" maxlength="50">{{ $course->content['up']['C'] }}</textarea></td>
                                            <td style="vertical-align: middle;"><textarea class="textarea" value="" cols="24" rows="5" name="up_D" maxlength="50">{{ $course->content['up']['D'] }}</textarea></td>
                                            <td style="vertical-align: middle;"><textarea class="textarea" value="" cols="24" rows="5" name="up_E" maxlength="50">{{ $course->content['up']['E'] }}</textarea></td>
                                            <td style="vertical-align: middle;"><textarea class="textarea" value="" cols="24" rows="5" name="up_F" maxlength="50">{{ $course->content['up']['F'] }}</textarea></td>
                                            <td style="vertical-align: middle;"><textarea class="textarea" value="" cols="24" rows="5" name="up_G" maxlength="50">{{ $course->content['up']['G'] }}</textarea></td>
                                            <td style="vertical-align: middle;"><textarea class="textarea" value="" cols="24" rows="5" name="up_H" maxlength="50">{{ $course->content['up']['H'] }}</textarea></td>
                                            <td style="vertical-align: middle;"><textarea class="textarea" value="" cols="24" rows="5" name="up_I" maxlength="50">{{ $course->content['up']['I'] }}</textarea></td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align: middle;">下午</td>
                                            <td style="vertical-align: middle;"><textarea class="textarea" value="" cols="24" rows="5" name="down_C" maxlength="50">{{ $course->content['down']['C'] }}</textarea></td>
                                            <td style="vertical-align: middle;"><textarea class="textarea" value="" cols="24" rows="5" name="down_D" maxlength="50">{{ $course->content['down']['D'] }}</textarea></td>
                                            <td style="vertical-align: middle;"><textarea class="textarea" value="" cols="24" rows="5" name="down_E" maxlength="50">{{ $course->content['down']['E'] }}</textarea></td>
                                            <td style="vertical-align: middle;"><textarea class="textarea" value="" cols="24" rows="5" name="down_F" maxlength="50">{{ $course->content['down']['F'] }}</textarea></td>
                                            <td style="vertical-align: middle;"><textarea class="textarea" value="" cols="24" rows="5" name="down_G" maxlength="50">{{ $course->content['down']['G'] }}</textarea></td>
                                            <td style="vertical-align: middle;"><textarea class="textarea" value="" cols="24" rows="5" name="down_H" maxlength="50">{{ $course->content['down']['H'] }}</textarea></td>
                                            <td style="vertical-align: middle;"><textarea class="textarea" value="" cols="24" rows="5" name="down_I" maxlength="50">{{ $course->content['down']['I'] }}</textarea></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="btn-group pull-left">
                            <button type="reset" class="btn btn-warning">重置</button>
                        </div>
                        <div class="btn-group pull-right">
                            <button type="button" id="submit-btn" class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin'></i> 提交">提交</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
            $('.form-history-back').on('click', function (event) {
                event.preventDefault();
                history.back();
            });

            $('#start').datetimepicker({
                format: 'YYYY-MM-DD',
                locale: moment.locale('zh-cn')
            });

            $("#submit-btn").click(function () {
                var $form = $("#post-form");
                $.ajax({
                    method: 'post',
                    url: '{{ route('admin::courses.submit') }}',
                    data: $form.serialize(),
                    success: function (data) {
                        if (typeof data === 'object') {
                            if (data.status) {
                                swal(data.message, '', 'success');
                            }
                        }
                    }
                });
            });
        });

        {{--$('#prev_week').click(function () {--}}
                {{--var date = $('#week_1').text();--}}
                {{--var week = getWeekList(new Date(date),'prev');--}}

                {{--$.ajax({--}}
                    {{--method: 'get',--}}
                    {{--url: '{{ route('admin::courses.setCourse',$collective->id) }}',--}}
                    {{--data: {--}}
                        {{--date: week[0],--}}
                        {{--_token: LA.token--}}
                    {{--},--}}
                    {{--success: function (data) {--}}
                        {{--if (typeof data === 'object') {--}}
                            {{--if (data.status) {--}}
                                {{--setContent(data.content);--}}
                            {{--}--}}
                            {{--else {--}}
                                {{--setContent(false);--}}
                            {{--}--}}
                            {{--$('input[name="begin_start"]').val(week[0]);--}}
                            {{--$.each(week,function(i,item){--}}
                                {{--$('#week_'+(i+1)).text(item);--}}
                            {{--});--}}
                        {{--}--}}
                    {{--}--}}
                {{--});--}}
        {{--});--}}

        {{--$('#next_week').click(function () {--}}
                {{--var date = $('#week_1').text();--}}
                {{--var week = getWeekList(new Date(date), 'next');--}}

                {{--$.ajax({--}}
                    {{--method: 'get',--}}
                    {{--url: '{{ route('admin::courses.setCourse',$collective->id) }}',--}}
                    {{--data: {--}}
                        {{--date: week[0],--}}
                        {{--_token: LA.token--}}
                    {{--},--}}
                    {{--success: function (data) {--}}
                        {{--if (typeof data === 'object') {--}}
                            {{--if (data.status) {--}}
                                {{--setContent(data.content);--}}
                            {{--}--}}
                            {{--else {--}}
                                {{--setContent(false);--}}
                            {{--}--}}
                            {{--$('input[name="begin_start"]').val(week[0]);--}}
                            {{--$.each(week, function (i, item) {--}}
                                {{--$('#week_' + (i + 1)).text(item);--}}
                            {{--});--}}
                        {{--}--}}
                    {{--}--}}
                {{--});--}}
        {{--});--}}

        {{--$('#current_week').click(function () {--}}
                {{--var date = $('#current_week1').val();--}}
                {{--var week = getWeekList(new Date(date), '');--}}
                {{--$.ajax({--}}
                    {{--method: 'get',--}}
                    {{--url: '{{ route('admin::courses.setCourse',$collective->id) }}',--}}
                    {{--data: {--}}
                        {{--date: $('#current_week1').val(),--}}
                        {{--_token: LA.token--}}
                    {{--},--}}
                    {{--success: function (data) {--}}
                        {{--if (typeof data === 'object') {--}}
                            {{--if (data.status) {--}}
                                {{--setContent(data.content);--}}
                            {{--}--}}
                            {{--else {--}}
                                {{--setContent(false);--}}
                            {{--}--}}
                            {{--$('input[name="begin_start"]').val(week[0]);--}}
                            {{--$.each(week, function (i, item) {--}}
                                {{--$('#week_' + (i + 1)).text(item);--}}
                            {{--});--}}
                        {{--}--}}
                    {{--}--}}
                {{--});--}}
        {{--});--}}

        /**
         * 获取日期
         * @param date
         * @returns {Array}
         */
        // function getWeekList(date,flag){
        //     var dateTime = date.getTime(); // 获取现在的时间
        //     var dateDay = date.getDay();
        //     if(dateDay == 0) dateDay = 7;
        //     var oneDayTime = 24 * 60 * 60 * 1000;
        //     var proWeekList = [];
        //
        //     if(flag == 'prev') {
        //         for(var i = 0; i < 7; i++){
        //             var time = dateTime - (dateDay + (7 - 1 - i)) * oneDayTime;
        //             proWeekList[i] = formatDate(new Date(time));
        //         }
        //     }
        //     else if(flag == 'next') {
        //         for(var i = 0; i < 7; i++){
        //             var time = dateTime + ((7 + 1 + i)-dateDay) * oneDayTime;
        //             proWeekList[i] = formatDate(new Date(time));
        //         }
        //     }
        //     else {
        //         for(var i = 0; i < 7; i++){
        //             var time = dateTime - (dateDay-(1 + i)) * oneDayTime;
        //             proWeekList[i] = formatDate(new Date(time));
        //         }
        //     }
        //
        //     return proWeekList;
        // }


        function formatDate(date) {
            var y = date.getFullYear();
            var m = date.getMonth() + 1;
            m = m < 10 ? ('0' + m) : m;
            var d = date.getDate();
            d = d < 10 ? ('0' + d) : d;
            // var h = date.getHours();
            // var minute = date.getMinutes();
            // minute = minute < 10 ? ('0' + minute) : minute;
            // var second= date.getSeconds();
            // second = minute < 10 ? ('0' + second) : second;
            return y + '-' + m + '-' + d;
        }

        function setContent(content) {
            var arr = ['C','D','E','F','G','H','I'];
            if(content) {
                $.each(arr,function (i,item) {
                    $("textarea[name=up_" + item + "]").val(content.up[item]);
                    $("textarea[name=down_" + item + "]").val(content.down[item]);
                })
            }
            else {
                $.each(arr,function (i,item) {
                    $("textarea[name=up_" + item + "]").val('');
                    $("textarea[name=down_" + item + "]").val('');
                })
            }
        }

        {{--$('#copy_course').click(function () {--}}
            {{--var date = $('#week_1').text();--}}
            {{--var class_id = $('input[name=class_id]').val();--}}
            {{--swal({--}}
                    {{--title: "确认复制上周课程?",--}}
                    {{--type: "warning",--}}
                    {{--showCancelButton: true,--}}
                    {{--confirmButtonColor: "#DD6B55",--}}
                    {{--confirmButtonText: "确认",--}}
                    {{--closeOnConfirm: false,--}}
                    {{--cancelButtonText: "取消"--}}
                {{--},--}}
                {{--function () {--}}
                    {{--$.ajax({--}}
                        {{--method: 'post',--}}
                        {{--url: '{{ route('admin::courses.getPrevWeek') }}',--}}
                        {{--data: {--}}
                            {{--date: date,--}}
                            {{--class_id:class_id,--}}
                            {{--_token: LA.token--}}
                        {{--},--}}
                        {{--success: function (data) {--}}
                            {{--if (typeof data === 'object') {--}}
                                {{--if (data.content) {--}}
                                    {{--setContent(data.content);--}}
                                    {{--swal('复制成功!', '', 'success');--}}
                                {{--} else {--}}
                                    {{--swal('上周无课程记录', '', 'error');--}}
                                {{--}--}}
                            {{--}--}}
                        {{--}--}}
                    {{--});--}}
                {{--});--}}
        {{--});--}}

        $("#downloadCourse").click(function(){
            var date = $('input[name=begin_start]').val();
            window.location.href='{{ route('admin::courses.exportTemplate',$collective->id) }}' + '?date=' + date;
        });

        $("#export").click(function(){
            var date = $('input[name=begin_start]').val();
            window.location.href='{{ route('admin::courses.export',$collective->id) }}' + '?date=' + date;
        });

        function uploadCourses(){
            swal({
                title: "文件上传中，请稍等",
                type: "info",
                showConfirmButton: false,
                closeOnConfirm: false
            });
            $.ajaxFileUpload({
                url: '{{ route('admin::courses.import',$collective->id) }}',
                secureuri: false,
                fileElementId: ['courseData'],//file控件id
                data: {
                    _token: LA.token
                },
                dataType : 'text',
                success: function (data) {
                    var data = eval('('+data+')');
                    if (typeof data === 'object') {
                        if (data.status) {
                            setContent(data.content);
                            //设置时间
                            // $('input[name="begin_start"]').val(data.week);
                            // var week = getWeekList(new Date(data.week), '');
                            // $.each(week, function (i, item) {
                            //     $('#week_' + (i + 1)).text(item);
                            // });
                            swal(data.message, '', 'success');
                        } else {
                            swal(data.message, '', 'error');
                        }
                    }
                }
            })
            $('#courseData').val('');
        }

    </script>
@endsection