@extends('admin::layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">编辑</h3>
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            <a href="{{ route('admin::students.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a>
                        </div> <div class="btn-group pull-right" style="margin-right: 10px">
                            <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                        </div>
                    </div>
                </div>
                <form id="post-form" class="form-horizontal" action="{{ route('admin::students.update',$student->id) }}" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    {{method_field('PUT')}}
                    <div class="box-body  add_parent">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">学生名</label>
                            <div class="col-sm-8">
                                <input type="text" id="name" name="name" value="{{$student->name}}" class="form-control title" placeholder="输入 学生名">
                            </div>
                        </div>



                        <div class="form-group">
                            <label for="avatar" class="col-sm-2 control-label">头像</label>
                            <div class="col-sm-8">
                                <input type="file" class="avatar" name="avatar" id="avatar">
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="username" class="col-sm-2 control-label">性别</label>
                            <div class="col-sm-8">
                                <select class="form-control category" style="width: 100%;" name="sex" data-placeholder=""  >
                                    <option value="" selected>请选择</option>
                                    <option value="1" @if($student->sex=='女')selected @endif>女</option>
                                    <option value="2" @if($student->sex=='男')selected @endif>男</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">出生年月</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" id="birthday" placeholder="出生年月" name="birthday" value="{{$student->birthday}}">
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="avatar" class="col-sm-2 control-label">请选择班级</label>
                            <div class="col-sm-3">
                                <select class="form-control grade" style="width: 100%;" name="grade_id" data-placeholder=""  >
                                    <option value="" >请选择年级</option>
                                    @foreach($grades as $grade)
                                        <option value="{{$grade->id}}" @if($student->collective->grade->id ==$grade->id) selected @endif>{{$grade->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select class="form-control class_id" style="width: 100%;" name="class_id" data-placeholder=""  >
                                    <option value="" >请选择班级</option>
                                    @foreach($classes as $class)
                                        <option value="{{$class->id}}" @if($student->class_id == $class->id)selected @endif>{{$class->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <a href="#" class="btn btn-primary btn-sm add_table" role="button">添加家长</a>
                        </div>
                        @foreach($parents as $parent)
                            <div class="form-group parent_table">
                                <label for="title" class="col-sm-2 control-label">家长角色</label>
                                <input type="hidden" name="ids[]" data-id="id" value ="{{$parent->id}}" >
                                <div class="col-sm-2">
                                    <input type="text" id="guarder" name="guarders[]" value="{{$parent->role($student->id)}}" class="form-control title" placeholder="输入 家长角色" >
                                </div>
                                <label for="title" class="col-sm-1 control-label">家长姓名</label>
                                <div class="col-sm-2">
                                    <input type="text" id="guardername" name="guardernames[]" value="{{$parent->name}}" class="form-control title" placeholder="输入 家长姓名" >
                                </div>
                                <label for="title" class="col-sm-1 control-label">家长联系方式</label>
                                <div class="col-sm-2">
                                    <input type="text" id="guardertel" name="guardertels[]" value="{{$parent->phone}}" class="form-control title" placeholder="输入 家长联系方式" readonly >
                                </div>
                                <a href="#" class="btn btn-primary btn-sm delete_table" role="button">删除家长</a>
                            </div>
                        @endforeach


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


            $('.add_table').click(function () {
                $('.add_parent').append("<div class=\"form-group parent_table\">\n" +
                    "                            <label for=\"title\" class=\"col-sm-2 control-label\">家长角色<\/label>\n" +
                    "                            <div class=\"col-sm-2\">\n" +
                    "                                <input type=\"text\" id=\"guarder\" name=\"guarder[]\" class=\"form-control title\" placeholder=\"输入 家长角色\">\n" +
                    "                            <\/div>\n" +
                    "                            <label for=\"title\" class=\"col-sm-1 control-label\">家长姓名<\/label>\n" +
                    "                            <div class=\"col-sm-2\">\n" +
                    "                                <input type=\"text\" id=\"guardername\" name=\"guardername[]\" class=\"form-control title\" placeholder=\"输入 监护人名字\">\n" +
                    "                            <\/div>\n" +
                    "                            <label for=\"title\" class=\"col-sm-1 control-label\">家长联系方式<\/label>\n" +
                    "                            <div class=\"col-sm-2\">\n" +
                    "                                <input type=\"text\" id=\"guardertel\" name=\"guardertel[]\" class=\"form-control title\" placeholder=\"输入 监护人联系方式\">\n" +
                    "                            <\/div>\n" +
                    "                          \n" +
                    "                        <a href=\"#\" class=\"btn btn-primary btn-sm delete_table\" role=\"button\">删除家长<\/a><\/div>");
            });
            $("body").on('click','.delete_table',function () {

            // })
            // $('.delete_table').click(function(){
                $uid = $(this).parent().find("input[data-id='id']").val();
                $sid = "{{$student->id}}";
                if($uid) {
                    swal(
                        {
                            title: "是否删除?",
                            text: "",
                            type: "warning",
                            showCancelButton: true,
                            cancelButtonText: "取消",
                            cancelButtonColor: "#B9B9B9",

                            showConfirmButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "确认",
                            closeOnConfirm: false,
                            closeOnCancel: true
                        },
                        function () {
                            $.ajax({
                                method: 'delete',
                                url: '/admin/student/parent' + '/' + $uid + '/' + $sid,
                                data: {
                                    _token: LA.token,
                                },
                                success: function (data) {
                                    $.pjax.reload('#pjax-container');

                                    if (typeof data === 'object') {
                                        if (data.status) {
                                            swal(data.message, '', 'success');
                                        } else {
                                            swal(data.message, '', 'error');
                                        }
                                    }
                                    $(this).parent().remove();
                                }
                            });
                        }
                    )
                }
                else{
                    $(this).parent().remove();
                }
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
                        var str=" <option value=\"\">请选择班级</option>";
                        for(var i=0;i<data.length;i++){
                            str = str+" <option value='"+data[i].id+"' >"+data[i].name+"</option>"
                        }
                        $(".class_id").html(str);
                    }
                });

            });




            ///
           var $rule ={
                live: 'enable',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    name:{
                        validators:{
                            notEmpty:{
                                message: '请输入学生姓名'
                            }
                        }
                    },
                    class_id:{
                        validators:{
                            notEmpty:{
                                message: '请输入学生姓名'
                            }
                        }
                    },
                    sex:{
                        validators:{
                            notEmpty:{
                                message: '请选择学生性别'
                            }
                        }
                    },
                    birthday:{
                        validators:{
                            notEmpty:{
                                message: '请输入学生出生日期'
                            }
                        }
                    },
                    'guarder[]':{
                        validators:{
                            notEmpty:{
                                message: '请输入学生监护人关系'
                            }
                        }
                    },
                    'guardername[]':{
                        validators:{
                            notEmpty:{
                                message: '请输入学生监护人姓名'
                            }
                        }
                    },
                    'guardertel[]':{
                        validators:{
                            notEmpty:{
                                message: '请输入学生监护人联系方式'
                            },
                            regexp: {
                                regexp: /^1[3|5|8|7]{1}[0-9]{9}$/,
                                message: '请输入正确的手机号码'
                            }

                        }
                    },
                    'guarders[]':{
                        validators:{
                            notEmpty:{
                                message: '请输入学生监护人关系'
                            }
                        }
                    },
                    'guardernames[]':{
                        validators:{
                            notEmpty:{
                                message: '请输入学生监护人姓名'
                            }
                        }
                    },
                    'guardertels[]':{
                        validators:{
                            notEmpty:{
                                message: '请输入学生监护人联系方式'
                            },
                            regexp: {
                                regexp: /^1[3|5|8|7]{1}[0-9]{9}$/,
                                message: '请输入正确的手机号码'
                            }
                        }
                    }
                }
            };



            var previewConfigs = [];
            var urls = [];
            var j = {};
            j.downloadUrl = "{{  $student->avatar}}";
            j.key = "{{ $student->id }}";
            previewConfigs.push(j);
            urls.push(j.downloadUrl);

            $("input.avatar").fileinput({
                "overwriteInitial": false,
                "initialPreviewAsData": true,
                "browseLabel": "浏览",
                initialPreview: urls,
                "showRemove": false,
                "showUpload": false,
                "allowedFileTypes": [
                    "image"
                ]
            });

            $("#submit-btn").click(function () {
                var $form = $("#post-form");
                $form.bootstrapValidator($rule);
                $form.bootstrapValidator('validate');
                if ($form.data('bootstrapValidator').isValid()) {
                    $form.submit();
                }
            });

            $('#birthday').datetimepicker({"format":"YYYY-MM-DD ","locale":"zh-CN"});


        });
    </script>
@endsection