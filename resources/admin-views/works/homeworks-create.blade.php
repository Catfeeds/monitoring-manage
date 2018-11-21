@extends('admin::layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">新增作业提醒</h3>
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            <a href="{{ route('admin::homeworks.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a>
                        </div> <div class="btn-group pull-right" style="margin-right: 10px">
                            <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                        </div>
                    </div>
                </div>

                <form id="post-form" class="form-horizontal" action="{{ route('admin::homeworks.store') }}" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group">
                            <label for="collective" class="col-sm-2 control-label">选择班级</label>
                            <div class="col-sm-8">
                                <select class="form-control collective" style="width: 100%;" id="collective" name="collective"  >
                                    <option value="" selected>请选择班级</option>
                                    @foreach($collectives as $collective)
                                        <option value="{{$collective->id}}" >{{$collective->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">作业标题</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                    <input type="text" id="title" name="title" class="form-control title" placeholder="输入 标题">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="content" class="col-sm-2 control-label">作业内容</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" id="editor" rows="8" name="content" placeholder="作业内容"></textarea>
                                <span id="error-info" style="font-size:85%;color:#a94442"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="end_at" class="col-sm-2 control-label">截止时间</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" id="end_at" name="end_at" class="form-control title" placeholder="截止时间">
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

            var editor = new Simditor({
                textarea: $('#editor'),
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
            editor.on('valuechanged',function () {
                $('#editor').text(editor.sync());
                if($('#editor').text()) {
                    $('.simditor').css('border-color','#00a65a');
                    $('#error-info').html('');
                    $('#submit-btn').prop('disabled',false);
                }
                else {
                    $('#error-info').html('请输入作业内容');
                    $('.simditor').css('border-color','#dd4b39');
                    $('#submit-btn').prop('disabled',true);
                }
            });

            //

            ///
            $("#post-form").bootstrapValidator({
                live: 'enabled',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    collective:{
                        validators:{
                            notEmpty:{
                                message: '请选择班级'
                            }
                        }
                    },
                    title:{
                        validators:{
                            notEmpty:{
                                message: '请输入标题'
                            },
                            stringLength: {
                                max: 10,
                                message: '标题长度不超过10个字符'
                            }
                        }
                    },
                    end_at:{
                        validators:{
                            notEmpty:{
                                message: '请输入时间'
                            },
                        }
                    },
                }
            });

            $("#submit-btn").click(function () {
                var $form = $("#post-form");
                if(!$('#editor').text()) {
                    $('#error-info').html('请输入作业内容');
                    $('.simditor').css('border-color','#dd4b39');
                    $('#submit-btn').prop('disabled',true);
                }

                $form.bootstrapValidator('validate');
                if ($form.data('bootstrapValidator').isValid()  && !$('#error-info').html()) {
                    $form.submit();
                }
            });
        });

        $('#end_at').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            locale: moment.locale('zh-cn')
        });

        $("#collective").select2({
            "allowClear": true
        });


        // $("#grade_radio").on('change',function () {
        //     $('.grade-select').css('display','block');
        // })
        //
        // $("#all_radio").on('change',function () {
        //     $('.grade-select').css('display','none');
        // })

    </script>
@endsection