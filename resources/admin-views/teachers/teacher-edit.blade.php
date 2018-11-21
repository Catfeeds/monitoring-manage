@extends('admin::layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">编辑</h3>
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            <a href="{{ route('admin::teachers.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a>
                        </div> <div class="btn-group pull-right" style="margin-right: 10px">
                            <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                        </div>
                    </div>
                </div>
                <form id="post-form" class="form-horizontal" action="{{ route('admin::teachers.update',$teacher->id) }}" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    {{ method_field('PUT')}}
                    <div class="box-body">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">教师名</label>
                            <div class="col-sm-8">
                                <input type="text" id="name" name="name" value ="{{$teacher->name}}" class="form-control title" value="" placeholder="输入 教师名">
                            </div>
                        </div>



                        <div class="form-group">
                            <label for="avatar" class="col-sm-2 control-label">教师头像</label>
                            <div class="col-sm-8">
                                <input type="file" class="avatar" name="avatar" id="avatar">
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="username" class="col-sm-2 control-label">教师性别</label>
                            <div class="col-sm-8">
                                <select class="form-control category" style="width: 100%;" name="sex" data-placeholder=""  >
                                    <option value="" selected>请选择</option>
                                    <option value="0" @if($teacher->sex == 0)selected @endif>女</option>
                                    <option value="1" @if($teacher->sex == 1)selected @endif>男</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="username" class="col-sm-2 control-label">是否班主任</label>
                            <div class="col-sm-8">
                                <select class="form-control category" style="width: 100%;" name="is_head" data-placeholder=""  >
                                    <option value="" selected>请选择</option>
                                    <option value="0" @if($teacher->is_head == 0)selected @endif>是</option>
                                    <option value="1" @if($teacher->is_head == 1)selected @endif>否</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="avatar" class="col-sm-2 control-label">请选择班级</label>
                            <div class="col-sm-3">
                                <select class="form-control grade" style="width: 100%;" name="grade" data-placeholder=""  >
                                    <option value="" >请选择年级</option>
                                    @foreach($grades as $grade)
                                        <option value="{{$grade->id}}" >{{$grade->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select class="form-control class_id" style="width: 100%;" name="class_id" data-placeholder=""  >
                                    <option value="" >请选择班级</option>

                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-github" type="button" id="add_class">添加班级</button>
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="avatar" class="col-sm-2 control-label"></label>
                            <div class="col-sm-6 class_set">
                                @foreach($classes as $class)
                                    <a class="btn  btn-info ss"  style="margin-right: 5px; margin-top: 5px" role="button" id="delete_class" >{{$class->grade->name.$class->name}}<input type="hidden" name="class_id[]" value="{{$class->id}}"></a>
                                @endforeach
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">备注</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" rows="5" id="note" name="note" placeholder="备注" >{{$teacher->note}}</textarea>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">教师联系方式</label>
                            <div class="col-sm-8">
                                <input type="text" id="tel" name="tel" value="{{$teacher->tel}}" class="form-control title" placeholder="输入 教师联系方式">
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

            $("body").on('click','.ss',function () {

                $(this).remove();
            });

            $('#add_class').click(function(){
                var class_id = $('.class_id');
                var grade_id = $('.grade');
                if(class_id.val()){
                    var set=$(".ss").find("input");
                    var flag=0;
                    set.each(function(){
                        if($(this).val() == class_id.val()){
                            flag=1;
                            return 0;
                        }
                    })
                    if(!flag)
                        $('.class_set').append('<a class="btn  btn-info ss"  style="margin-right: 5px; margin-top: 5px" role="button" id="delete_class" >'+'<input type="hidden" name="class_id[]" value="'+class_id.val()+'">'+grade_id.find("option:selected").text()+class_id.find("option:selected").text()+'<\/a>');
                    else{
                        swal(
                            '此班级已经添加过了',
                            '请重新选择',
                            ''
                        )
                    }
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
                    name:{
                        validators:{
                            notEmpty:{
                                message: '请输入教师姓名'
                            }
                        }
                    },
                    class_id:{
                        validators:{
                            notEmpty:{
                                message: '请输入教师姓名'
                            }
                        }
                    },
                    sex:{
                        validators:{
                            notEmpty:{
                                message: '请选择教师性别'
                            }
                        }
                    },
                    tel:{
                        validators:{
                            notEmpty:{
                                message: '请输入教师联系方式'
                            },
                            regexp: {
                                regexp: /^1[3|5|8]{1}[0-9]{9}$/,
                                message: '请输入正确的手机号码'
                            }
                        }
                    },
                    note:{
                        validators:{
                            notEmpty:{
                                message: '请输入教师备注'
                            }
                        }
                    },
                    is_head:{
                        validators:{
                            notEmpty:{
                                message: '请选择是否校长'
                            }
                        }
                    }
                }
            });


            var previewConfigs = [];
            var urls = [];
            var j = {};
            j.downloadUrl = "{{ $teacher->avatar }}";
            j.key = "{{ $teacher->id }}";
            previewConfigs.push(j);
            urls.push(j.downloadUrl);

            $("input.avatar").fileinput({
                "overwriteInitial": false,
                "initialPreviewAsData": true,
                "browseLabel": "浏览",
                "showRemove": false,
                "showUpload": false,
                initialPreview: urls,
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