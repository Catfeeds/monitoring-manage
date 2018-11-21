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
                    <h3 class="box-title">学生食谱</h3>
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            {{--<a href="{{ route('admin::recipes.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a>--}}
                        </div> <div class="btn-group pull-right" style="margin-right: 10px">
                            {{--<a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>--}}
                            <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-refresh"></i>&nbsp;刷新</a>
                        </div>
                    </div>
                </div>
                <form id="post-form" class="form-horizontal" action="{{ route('admin::recipes.update',$recipe->id) }}" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="box-body">
                        <div class="fields-group">
                            <div class="form-group">
                                {{--<label for="scope" class="col-sm-1 control-label">开始时间</label>--}}
                                {{--<div class="col-sm-4">--}}
                                    {{--<div class="input-group">--}}
                                        {{--<span class="input-group-addon"><i class="fa fa-calendar"></i></span>--}}
                                        {{--<input type="text" class="form-control" id="start" value="{{ $recipe->begin_start }}" disabled placeholder="开始时间" name="begin_start">--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-sm-5">--}}
                                    {{--<input class="btn btn-success" id="copy_recipe" type="button" value="复制上周食谱" />--}}
                                {{--</div>--}}

                                <div class="col-sm-1">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" >
                                            <i class="fa fa-upload" aria-hidden="true"></i>&nbsp;
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
                                {{--<label for="title" class="col-sm-1 control-label">每周食谱</label>--}}
                                <div class="col-sm-12 ">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" style="border-top:1px solid #ddd;white-space:nowrap;" >
                                            <thead>
                                            <tr>
                                                <th style="vertical-align: middle;">日程/餐段</th>
                                                @foreach($recipe->tags as $k=>$tag)
                                                    <th id="{{ $k }}" class="title" style="vertical-align: middle;">{{ $tag }}</th><input type="hidden"  name="{{ $k }}" value="{{ $tag }}"/>
                                                @endforeach
                                            </tr>
                                            </thead>
                                            @foreach($recipe->content as $k => $v)
                                                <tr>
                                                    <td style="vertical-align: middle;">{{ $dates[$k] }}</td>
                                                    @foreach($recipe->tags as $k1=>$tag)
                                                        <td><textarea class="textarea" value="" cols="24" rows="5" name="{{ $k.'_'.$k1 }}" maxlength="50">{{ $v[$k1] }}</textarea></td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="btn-group pull-left">
                            <button type="reset" class="btn btn-warning">重置</button>
                        </div>
                        <div class="btn-group pull-right">
                            <span id="prompt-info" style="color:#f00;"></span>
                            <button type="button" data-tags="{{ json_encode($tags) }}" data-dates="{{ json_encode($dates) }}" id="submit-btn"  class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin'></i> 提交">提交</button>
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
                location.reload();
            });

            $('.title').click(function(){
                if(!$(this).is('.input')){
                    $(this).addClass('input').html('<input type="text" maxlength="10" class="form-control center" value="'+ $.trim($(this).text()) +'" />').find('input').focus().blur(function(){
                        $(this).parent().next().val($.trim($(this).val()) || "")
                        $(this).parent().removeClass('input').text($.trim($(this).val()) || "");
                    });
                }
            }).hover(function(){
                $(this).addClass('hover');
            },function(){
                $(this).removeClass('hover');
            });

            var inTag = 0;

            $("#submit-btn").click(function () {
                var $form = $("#post-form");
                var tags = $(this).data('tags');
                var flag = 0;
                $.each(tags,function (k,v) {
                    flag = 0;
                    $("textarea[name$="+v+"]").each(function () {
                        if($(this).val()!="") {
                            flag = 1;
                            return false;
                        }
                    });
                    if($("#"+v).text() == '' && flag) {
                        console.log($("#"+v).text());
                        inTag = k+1;
                        return false;
                    }
                    else {
                        inTag = 0;
                    }
                })
                if(inTag) {
                    swal("食谱第"+(inTag+1)+"列标题不能为空！","请继续操作！","warning");
                    return false;
                }

                $form.bootstrapValidator('validate');
                if ($form.data('bootstrapValidator').isValid()) {
                    $.ajax({
                        method: 'post',
                        url: '{{ route('admin::recipes.update',$recipe->id) }}',
                        data: $form.serialize(),
                        success: function (data) {
                            if (typeof data === 'object') {
                                if (data.status) {
                                    swal(data.message, '', 'success');
                                }
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection