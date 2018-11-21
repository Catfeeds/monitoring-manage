@extends('admin::layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">支付设置</h3>
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                        </div>
                    </div>
                </div>
                <form id="post-form" class="form-horizontal" action="{{ route('admin::payConfigs.update',$payConfig->id) }}" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="box-body">
                        <div class="fields-group">
                            <div class="form-group">
                                <label  class="col-sm-2 control-label"></label>
                                <div class="col-sm-8">
                                    <h3>缴费信息设置</h3>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="title" class="col-sm-2 control-label">缴费标题</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" class="form-control" value="{{ $payConfig->title }}" id="title" name="title"  placeholder="输入 缴费标题">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="content" class="col-sm-2 control-label">缴费内容</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" id="editor" rows="8" name="content" placeholder="缴费内容">{{ $payConfig->content }}</textarea>
                                    <span id="error-info" style="font-size:85%;color:#a94442"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-2 control-label"></label>
                                <div class="col-sm-8">
                                    <h3>银行转账信息设置</h3>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="bank_name" class="col-sm-2 control-label">银行名称</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" class="form-control" value="{{ $payConfig->bank_name }}" id="bank_name" name="bank_name"  placeholder="输入 银行名称">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="bank_card" class="col-sm-2 control-label">银行卡号</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" class="form-control" value="{{ $payConfig->bank_card }}" id="bank_card" name="bank_card"  placeholder="输入 银行卡号">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="bank_man" class="col-sm-2 control-label">开户人</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" class="form-control" value="{{ $payConfig->bank_man }}" id="bank_man" name="bank_man"  placeholder="输入 开户人">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="bank_place" class="col-sm-2 control-label">开户地址</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" class="form-control" value="{{ $payConfig->bank_place }}" id="bank_place" name="bank_place"  placeholder="输入 开户地址">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label  class="col-sm-2 control-label"></label>
                                <div class="col-sm-8">
                                    <h3>支付宝支付设置</h3>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="alipay_code" class="col-sm-2 control-label">开户地址</label>
                                <div class="col-sm-8">
                                    <input type="file" class="alipay_code" name="alipay_code" id="alipay_code" accept="image/*">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label  class="col-sm-2 control-label"></label>
                                <div class="col-sm-8">
                                    <h3>微信支付设置</h3>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="wechat_code" class="col-sm-2 control-label">微信支付二维码</label>
                                <div class="col-sm-8">
                                    <input type="file" class="wechat_code" name="wechat_code" id="wechat_code" accept="image/*">
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
                            <button type="button" id="submit-btn"  class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin'></i> 提交">提交</button>
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
                location.reload();
            });

            var urls = [];
            var j = {};
            var url = "{{$payConfig->alipay_code}}";
            var previewConfigs1 = [];
            if(url) {
                j.downloadUrl = "{{ $payConfig->alipay_code }}";
                j.key = "{{ $payConfig->id }}";
                previewConfigs1.push(j);
                urls.push(j.downloadUrl);
            }

            $(".alipay_code").fileinput({
                overwriteInitial: false,
                initialPreviewAsData: true,
                initialPreview: urls,
                initialPreviewConfig: previewConfigs1,
                deleteUrl: "{{ route('admin::upload.delete_cover') }}",
                deleteExtraData: {
                    _method:'put',
                    _token: LA.token,
                    type:'alicode'
                },
                browseLabel: "浏览",
                showRemove: false,
                showUpload: false,
                allowedFileTypes: [
                    "image"
                ]
            });

            var wechat_urls = [];
            var previewConfigs = [];
            var j1 = {};
            var wechat_url = "{{$payConfig->wechat_code}}";
            if(wechat_url) {
                j1.downloadUrl = "{{ $payConfig->wechat_code }}";
                j1.key = "{{ $payConfig->id }}";
                previewConfigs.push(j1);
                wechat_urls.push(j1.downloadUrl);
            }

            $(".wechat_code").fileinput({
                overwriteInitial: false,
                initialPreviewAsData: true,
                initialPreview: wechat_urls,
                initialPreviewConfig: previewConfigs,
                deleteUrl: "{{ route('admin::upload.delete_cover') }}",
                deleteExtraData: {
                    _method:'put',
                    _token: LA.token,
                    type:'wechat'
                },
                browseLabel: "浏览",
                showRemove: false,
                showUpload: false,
                allowedFileTypes: [
                    "image"
                ]
            });

            var editor = new Simditor({
                textarea: $('#editor'),
                //支持图片黏贴
                pasteImage: false,
                toolbar: [
                    'title',
                    'bold',
                    'italic',
                    'underline',
                    'strikethrough',
                    'fontScale',
                    'color',
                    'ol',
                    'ul',
                    'blockquote',
                    'code',
                    'table',
                    'link',
                    'hr',
                    'indent',
                    'outdent',
                    'alignment'
                ],
                toolbarFloat: true
            });
            editor.on('valuechanged',function () {
                $('#editor').text(editor.sync());
                if($('#editor').text()) {
                    $('.simditor').css('border-color','#00a65a');
                    $('#error-info').html('');
                    $('#submit-btn').prop('disabled',false);
                }
                else {
                    $('#error-info').html('请输入缴费内容');
                    $('.simditor').css('border-color','#dd4b39');
                    $('#submit-btn').prop('disabled',true);
                }
            });

            $("#post-form").bootstrapValidator({
                live: 'enable',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    title:{
                        validators:{
                            notEmpty:{
                                message: '请输入标题'
                            },
                            stringLength: {
                                max: 50,
                                message: '标题长度不超过50个字符'
                            }
                        }
                    },
                    bank_name:{
                        validators:{
                            notEmpty:{
                                message: '请输入银行名称'
                            }
                        }
                    },
                    bank_name:{
                        validators:{
                            notEmpty:{
                                message: '请输入银行名称'
                            }
                        }
                    },
                    bank_card:{
                        validators:{
                            notEmpty:{
                                message: '请输入银行卡号'
                            },
                        }
                    },
                    bank_man:{
                        validators:{
                            notEmpty:{
                                message:'请输入开户人'
                            }
                        }
                    },
                    bank_place:{
                        validators:{
                            notEmpty:{
                                message:'请输入开户地址'
                            }
                        }
                    }
                }
            });

            $("#submit-btn").click(function () {
                var $form = $("#post-form");
                if(!$('#editor').text()) {
                    $('#error-info').html('请输入通知内容');
                    $('.simditor').css('border-color','#dd4b39');
                    $('#submit-btn').prop('disabled',true);
                }

                $form.bootstrapValidator('validate');
                if ($form.data('bootstrapValidator').isValid()  && !$('#error-info').html()) {
                    $form.submit();
                }
            })
        });

    </script>
@endsection