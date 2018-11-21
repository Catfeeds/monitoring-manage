@extends('admin::layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">创建</h3>
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            <a href="{{ route('admin::schools.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a>
                        </div> <div class="btn-group pull-right" style="margin-right: 10px">
                            <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                        </div>
                    </div>
                </div>
                <form id="post-form" class="form-horizontal" action="{{ route('admin::schools.store') }}" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">学校名</label>
                            <div class="col-sm-8">
                                <input type="text" id="sname" name="sname" class="form-control title" placeholder="输入 学校名">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="avatar" class="col-sm-2 control-label">校标</label>
                            <div class="col-sm-8">
                                <input type="file" class="avatar_school" name="avatar_school" accept="image/*">
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="phone" class="col-sm-2 control-label">学校联系方式</label>
                            <div class="col-sm-8">
                                <input type="text"  class="form-control title" name="phone"  placeholder="输入 学校联系方式">
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="address" class="col-sm-2 control-label">学校地址</label>
                            <div class="col-sm-8">
                                <input type="text"  class="form-control title" name="address"  placeholder="输入 学校地址">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="sence_school" class="col-sm-2 control-label">校园风光</label>
                            <div class="col-sm-8">
                                <input type="file" class="sence_school" name="sence_school[]" accept="image/*" multiple>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address" class="col-sm-2 control-label">校园简介</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" id="editor" rows="8" name="describe" placeholder="校园简介"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address" class="col-sm-2 control-label">招生信息</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" id="editor2" rows="8" name="detail_info" placeholder="校园简介"></textarea>
                            </div>
                        </div>




                        <div class="form-group">
                            <label for="title" class="col-sm-12 control-label"><hr/></label>
                        </div>

                        <div class="form-group">
                            <label for="username" class="col-sm-2 control-label">校园管理者帐号</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                    <input type="text" id="username" name="username" value="" class="form-control username" placeholder="输入 校园管理者帐号">
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">昵称</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                    <input type="text" id="name" name="name" value="" class="form-control name" placeholder="输入 昵称">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="avatar" class="col-sm-2 control-label">校园管理者头像</label>
                            <div class="col-sm-8">
                                <input type="file" class="avatar" name="avatar" id="avatar">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">密码</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-eye-slash"></i></span>
                                    <input type="password" id="password" name="password" value="" class="form-control password" placeholder="输入 密码">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation" class="col-sm-2 control-label">确认密码</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-eye-slash"></i></span>
                                    <input type="password" id="password_confirmation" name="password_confirmation" value="" class="form-control password_confirmation" placeholder="输入 确认密码">
                                </div>
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

            {{--var editor = new Simditor({--}}
                {{--textarea: $('#editor'),--}}
                {{--upload: {--}}
                    {{--//处理上传图片的URL--}}
                    {{--url: '{{ route('admin::upload.upload_image') }}',--}}
                    {{--//防止crsf跨站请求--}}
                    {{--params: { _token: '{{ csrf_token() }}' },--}}
                    {{--//服务器端获取图片的键值--}}
                    {{--fileKey: 'upload_file',--}}
                    {{--//最多允许上传图片数--}}
                    {{--connectionCount: 3,--}}
                    {{--//上传时关闭页面提醒--}}
                    {{--leaveConfirm: '文件上传中，关闭此页面将取消上传。'--}}
                {{--},--}}
                {{--//支持图片黏贴--}}
                {{--pasteImage: true,--}}
            {{--});--}}


            var editor2 = new Simditor({
                textarea: $('#editor2'),
                upload: {
                    //处理上传图片的URL
                    url: '{{ route('admin::upload.upload_image') }}',
                    //防止crsf跨站请求
                    params: { _token: '{{ csrf_token() }}' },
                    //服务器端获取图片的键值
                    fileKey: 'upload_file',
                    //最多允许上传图片数
                    connectionCount: 3,
                    //上传时关闭页面提醒
                    leaveConfirm: '文件上传中，关闭此页面将取消上传。'
                },
                //支持图片黏贴
                pasteImage: true,
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
                    sname:{
                        validators:{
                            notEmpty:{
                                message: '请输入学校名称'
                            }
                        }
                    },
                    avatar:{
                        validators:{
                            notEmpty:{
                                message:'请选择学校校标'
                            }
                        }
                    },
                    name:{
                        validators:{
                            notEmpty:{
                                message: '请输入用户别名'
                            }
                        }
                    },
                    username:{
                        validators:{
                            notEmpty:{
                                message: '请输入用户名称'
                            }
                        }
                    },
                    password:{
                        validators:{
                            notEmpty:{
                                message: '请输入用户密码'
                            }
                        }
                    },
                    password_confirmation:{
                        validators:{
                            notEmpty:{
                                message: '请再一次输入密码'
                            }
                        }
                    },
                    phone:{
                        validators:{
                            notEmpty:{
                                message: '请输入学校联系方式'
                            }
                        }
                    },
                    address:{
                        validators:{
                            notEmpty:{
                                message: '请输入学校地址'
                            }
                        }
                    },
                    describe:{
                        validators:{
                            notEmpty:{
                                message: '请输入学校简介'
                            }
                        }
                    },
                    detail_info:{
                        validators:{
                            notEmpty:{
                                message: '请输入学校招生信息'
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

            $("input.sence_school").fileinput({
                "overwriteInitial": false,
                "initialPreviewAsData": true,
                "browseLabel": "浏览",
                "showRemove": false,
                "showUpload": false,
                "allowedFileTypes": [
                    "image"
                ]
            });

            $("input.avatar_school").fileinput({
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