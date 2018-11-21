@extends('admin::layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            <a href="{{ route('admin::cameras.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a>
                        </div> <div class="btn-group pull-right" style="margin-right: 10px">
                            <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                        </div>
                    </div>
                </div>
                <form id="post-form" class="form-horizontal" action="{{ route('admin::cameras.update',$camera->id) }}" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    {{method_field('PUT')}}
                    <div class="box-body">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">uid</label>
                            <div class="col-sm-8">
                                <input type="text" id="uid" name="uid" value="{{$camera->uid}}" class="form-control title" placeholder="输入 uid">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="area" class="col-sm-2 control-label">摄像头名称</label>
                            <div class="col-sm-8">
                                <input type="text" id="area" name="area" value="{{$camera->area}}" class="form-control area" placeholder="输入 摄像头名称">
                                <span style="color: orangered;">名称例子：走廊视频、班级内视频等</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="avatar" class="col-sm-2 control-label">请选择班级</label>
                            <div class="col-sm-3">
                                <select class="form-control grade" style="width: 100%;" name="grade" data-placeholder=""  >
                                    <option value="" >请选择年级</option>
                                    @foreach($grades as $grade)
                                        <option value="{{$grade->id}}" @if($camera->collective->grade->id ==$grade->id) selected @endif>{{$grade->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select class="form-control class_id" style="width: 100%;" name="class_id" data-placeholder=""  >
                                    <option value="" >请选择班级</option>
                                    @foreach($classes as $class)
                                        <option value="{{$class->id}}" @if($camera->class_id == $class->id)selected @endif>{{$class->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>





                    </div>
                    <div class="box-footer">
                        <div class="btn-group pull-left">
                            <button type="reset" class="btn btn-warning">重置</button>
                        </div>
                        <div class="btn-group pull-right">
                            <button type="submit" id="submit-btn" class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin'></i> 提交">提交</button>
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

            $(".grade").change(function () {

                var id = $(this).val();
                $.ajax({
                    type:"get",
                    dataType:"json",
                    data:{
                        _token:LA.token
                    },
                    url:"/admin/collectives/"+id,
                    success: function(data){
                        console.log(data)
                        var str=" <option value=\"\">请选择班级</option>";
                        for(var i=0;i<data.length;i++){
                            str = str+" <option value='"+data[i].id+"' >"+data[i].name+"</option>"
                        }
                        $(".class_id").html(str);
                    }
                });

            });

            ///
            $("#post-form").bootstrapValidator({
                live: 'enable',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    uid:{
                        validators:{
                            notEmpty:{
                                message: '请输入uid'
                            }
                        }
                    },
                    area:{
                        validators:{
                            notEmpty:{
                                message: '请输入摄像头名称'
                            },
                            stringLength: {
                                max: 10,
                                message: '摄像头名称长度不能超过10个字符'
                            }
                        }
                    },
                    class_id:{
                        validators:{
                            notEmpty:{
                                message: '请选择班级'
                            }
                        }
                    }
                }
            });



            $("input.avatar").fileinput({
                "overwriteInitial": false,
                "initialPreviewAsData": true,
                "browseLabel": "浏览",
                "showRemove": false,
                "showUpload": false,
                "allowedFileTypes": [
                    "image"
                ]
            });

            $("#submit-btn").click(function () {
                var $form = $("#post-form");

                $form.bootstrapValidator('validate');
                if ($form.data('bootstrapValidator').isValid()) {
                    $form.submit();
                }
            });


        });
    </script>
@endsection