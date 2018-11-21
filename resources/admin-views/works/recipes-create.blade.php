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
                    <h3 class="box-title">新增食谱</h3>
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            <a href="{{ route('admin::recipes.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a>
                        </div> <div class="btn-group pull-right" style="margin-right: 10px">
                            <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                        </div>
                    </div>
                </div>

                <form id="post-form" class="form-horizontal" action="{{ route('admin::recipes.store') }}" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group">
                            <label for="scope" class="col-sm-1 control-label">开始时间</label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" id="start" placeholder="开始时间" name="begin_start">
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <input class="btn btn-success" id="copy_recipe" type="button" value="复制上周食谱" />
                            </div>
                            <div class="col-sm-1">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" >
                                        导入 <span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <div style="height:30px;position:relative;width:100px;margin-top:5px;">
                                            <a style="margin-left: 20px;">导入</a>
                                            <input style="height:30px; width:100px;overflow: hidden;position:absolute;right:0;top:0;float:left;opacity: 0;filter:alpha(opacity=0);cursor:pointer;" type="file" name="foodData" id="foodData" accept=".xls,.xlsx" onchange="uploadFoods()" />
                                            </div>
                                        </li>
                                        <li><a href="javascript:void(0);" id="downloadFood" style="color: #428bca;">下载导入模板</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-1 control-label">每周食谱</label>
                            <div class="col-sm-10 ">
                                <div class="table-responsive">
                                <table class="table table-bordered" style="border-top:1px solid #ddd;white-space:nowrap;" >
                                    <thead>
                                        <tr>
                                            <th style="vertical-align: middle;">日程/餐段</th>
                                            <th id="one" class="title" style="vertical-align: middle;">早餐</th><input type="hidden"  name="one" value="早餐"/>
                                            <th id="two" class="title" style="vertical-align: middle;">早餐加餐</th><input type="hidden" name="two" value="早餐加餐"/>
                                            <th id="three" class="title" style="vertical-align: middle;">午餐</th><input type="hidden" name="three" value="午餐"/>
                                            <th id="four" class="title" style="vertical-align: middle;">午餐加餐</th><input type="hidden" name="four" value="午餐加餐"/>
                                            <th id="five" class="title" style="vertical-align: middle;">晚餐</th><input type="hidden" name="five" value="晚餐"/>
                                            <th id="six" class="title" style="vertical-align: middle;"></th><input type="hidden" name="six" value=""/>
                                            <th id="seven" class="title" style="vertical-align: middle;"></th><input type="hidden" name="seven" value=""/>
                                        </tr>
                                    </thead>
                                    @foreach($dates as $k => $date)
                                    <tr>
                                        <td style="vertical-align: middle;">{{ $date }}</td>
                                        @foreach($tags as $v)
                                            <td><textarea class="textarea" value="" cols="24" rows="5" name="{{ $k.'_'.$v }}" maxlength="50"></textarea></td>
                                        @endforeach
                                    </tr>
                                    @endforeach
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
                            <button type="button" data-tags="{{ json_encode($tags) }}" data-dates="{{ json_encode($dates) }}" id="submit-btn" class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin'></i> 提交">提交</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.copy-prev-recipe')
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

            var inTag = 0;

            $("#submit-btn").click(function () {
                var $form = $("#post-form");
                var sDate = $("#start").val();
                var tags = $(this).data('tags');
                var flag = 0;
                $.each(tags, function (k, v) {
                    flag = 0;
                    $("textarea[name$=" + v + "]").each(function () {
                        if ($(this).val() != "") {
                            flag = 1;
                            return false;
                        }
                    });
                    if ($("#" + v).text() == '' && flag) {
                        inTag = k + 1;
                        return false;
                    }
                    else {
                        inTag = 0;
                    }
                })

                if (sDate != '') {
                    var weekDay = getMyDay(new Date(sDate));
                    if (weekDay != "周一") {
                        swal("开始日期不是周一！", "请继续操作！", "warning");
                        return false;
                    }
                }
                if (sDate == '') {
                    swal("请选择开始日期!", "请继续操作！", "warning");
                    return false;
                }
                if (inTag) {
                    swal("食谱第" + (inTag + 1) + "列标题不能为空！", "请继续操作！", "warning");
                    return false;
                }

                $.ajax({
                    method: 'get',
                    url: '{{ route('admin::recipes.checkDate') }}' + '?date=' + sDate,
                    data: {
                        _token: LA.token
                    },
                    success: function (data) {
                        if (typeof data === 'object') {
                            if (data.status) {
                                $form.submit();
                            } else {
                                swal(data.message, '', 'error');
                            }
                        }
                    }
                });
            });

            $('.title').click(function () {
                if (!$(this).is('.input')) {
                    $(this).addClass('input').html('<input type="text" maxlength="10" class="form-control center" value="' + $.trim($(this).text()) + '" />').find('input').focus().blur(function () {
                        $(this).parent().next().val($.trim($(this).val()) || "")
                        $(this).parent().removeClass('input').text($.trim($(this).val()) || "");
                    });
                }
            }).hover(function () {
                $(this).addClass('hover');
            }, function () {
                $(this).removeClass('hover');
            });
        });



    </script>
@endsection