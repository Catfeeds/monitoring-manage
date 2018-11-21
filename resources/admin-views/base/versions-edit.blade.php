@extends('admin::layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">版本设置</h3>
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;刷新</a>
                        </div>
                    </div>
                </div>
                <form id="post-form" class="form-horizontal" action="" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="fields-group">
                            <div class="form-group">
                                <label  class="col-sm-2 control-label"></label>
                                <div class="col-sm-8">
                                    <h3>园丁端版本设置</h3>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="version_no" class="col-sm-2 control-label">版本号</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" class="form-control" id="version_no"  name="version_no" value="{{ $version->version_no }}" placeholder="App版本号" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="download_file" class="col-sm-2 control-label">apk文件</label>
                                <div class="col-sm-8">
                                    <input type="file" class="form-control version" id="download_file" name="download_file" value="{{ Storage::url($version->download_url) }}" accept=".apk"  placeholder="文件" />
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label  class="col-sm-2 control-label"></label>
                            <div class="col-sm-8">
                                <h3>家长端版本设置</h3>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="vergrad_no" class="col-sm-2 control-label">版本号</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                    <input type="text" class="form-control" id="vergrad_no"  name="vergrad_no" value="{{ $version->vergrad_no }}" placeholder="App版本号" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="grad_url" class="col-sm-2 control-label">apk文件</label>
                            <div class="col-sm-8">
                                <input type="file" class="form-control version" id="grad_url" name="grad_url" value="{{ Storage::url($version->grad_url) }}" accept=".apk"  placeholder="文件" />
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

            $("#post-form").bootstrapValidator({
                live: 'enabled',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    version_no:{
                        validators:{
                            notEmpty:{
                                message: '请输入版本号'
                            },
                        }
                    },
                    vergrad_no:{
                        validators:{
                            notEmpty:{
                                message: '请输入版本号'
                            },
                        }
                    }
                }
            });

            {{--var urls = [];--}}
            {{--var j = {};--}}
            {{--var url = "{{ Storage::url($version->download_url)}}";--}}
            {{--if(url) {--}}
            {{--j.downloadUrl = "{{ Storage::url($version->download_url) }}";--}}
            {{--urls.push(j.downloadUrl);--}}
            {{--}--}}

            $(".version").fileinput({
                overwriteInitial: false,
                // initialPreviewAsData: true,
                // initialPreview: urls,
                browseLabel: "浏览",
                showRemove: false,
                showUpload: false,
                allowedFileExtensions: ['apk'],
            });

            $(".file-caption-name:first").html('<i class=\'fa fa-file\'></i>'+"{{Storage::url($version->download_url)}}");

            $(".file-caption-name:last").html('<i class=\'fa fa-file\'></i>'+"{{Storage::url($version->grad_url)}}");


            $('#submit-btn').on('click', function (event) {
                var $form = $("#post-form");

                $form.bootstrapValidator('validate');
                if ($form.data('bootstrapValidator').isValid()) {
                    swal({
                        title: "文件上传中，请稍等",
                        type: "info",
                        showConfirmButton: false,
                        closeOnConfirm: false
                    });
                    $.ajaxFileUpload({
                        method: 'put',
                        url: '{{ route('admin::versions.update',$version->id) }}',
                        secureuri: false,
                        fileElementId: ['download_file','grad_url'],//file控件id
                        data: {
                            vergrad_no:$('#vergrad_no').val(),
                            version_no:$('#version_no').val(),
                            _token: LA.token
                        },
                        dataType : 'text',
                        success: function (data) {
                            var data = eval('('+data+')');
                            if (typeof data === 'object') {
                                if (data.status) {
                                    $.pjax.reload('#pjax-container');
                                    swal(data.message, '', 'success');
                                }
                            }
                        }
                    })
                }
            });
        });
    </script>
@endsection