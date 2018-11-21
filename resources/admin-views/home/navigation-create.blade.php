@extends('admin::layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">创建</h3>
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            <a href="{{ route('admin::navigations.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a>
                        </div> <div class="btn-group pull-right" style="margin-right: 10px">
                            <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                        </div>
                    </div>
                </div>
                <form id="post-form" class="form-horizontal" action="{{ route('admin::navigations.store') }}" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">标题</label>
                            <div class="col-sm-8">
                                <input type="text" id="title" name="title" class="form-control title" placeholder="输入 标题">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="order" class="col-sm-2 control-label">排序</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" id="order" name="order" value="0" class="form-control order" placeholder="输入 排序">
                                </div>
                            </div>
                        </div>
                        <div class="fields-group">
                            <div class="form-group">
                                <label for="icon" class="col-sm-2 control-label">图标</label>
                                <div class="col-sm-8">
                                    <input type="file" class="icon" name="icon" id="icon" accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div class="fields-group">
                            <div class="form-group">
                                <label for="type" class="col-sm-2 control-label">类型</label>
                                <div class="col-sm-8">
                                    <select id="type-name" class="form-control type" style="width: 100%;" name="type" data-placeholder="选择 类型"  >
                                        <option value="">请选择</option>
                                        @foreach($types as $k => $type)
                                            <option value="{{ $k }}" >{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label for="target" class="col-sm-2 control-label">目标</label>
                            <div class="col-sm-8">
                                <div class="input-group" style="width: 100%">
                                    <div id="http" class="input-group-btn" style="display: none;">
                                        <select name="http" class="btn btn-default" style="background-color: white;">
                                            <option value="1" selected >http://</option>
                                            <option value="2">https://</option>
                                        </select>
                                    </div>
                                    <input type="hidden" id="target" name="target" class="form-control target">
                                    <input type="text" id="faker_target"  name="faker_target" class="form-control" placeholder="输入 目标" style="width: 100%;">
                                </div>
                                {{--<span class="help-block">根据选择的类型填写本字段内容，选择“链接”需填写完整链接地址</span>--}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-sm-2 control-label">启用</label>
                            <div class="col-sm-8">
                                <input type="checkbox" class="status la_checkbox" checked/>
                                <input type="hidden" class="status" name="status" value="1"/>
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

            $(".order").bootstrapNumber({
                'upClass': 'success',
                'downClass': 'primary',
                'center': true
            });

            $(".icon").fileinput({
                overwriteInitial: false,
                initialPreviewAsData: true,
                browseLabel: "浏览",
                showRemove: false,
                showUpload: false,
                allowedFileTypes: [
                    "image"
                ]
            });

            $(".type").select2({
                "allowClear": true
            });

            $('.status.la_checkbox').bootstrapSwitch({
                size:'small',
                onText: '是',
                offText: '否',
                onColor: 'primary',
                offColor: 'danger',
                onSwitchChange: function(event, state) {
                    $(event.target).closest('.bootstrap-switch').next().val(state ? '1' : '0').change();
                }
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
                    title:{
                        validators:{
                            notEmpty:{
                                message: '请输入标题'
                            }
                        }
                    },
                    type:{
                        validators:{
                            notEmpty:{
                                message:'请选择类型'
                            }
                        }
                    },
                    faker_target:{
                        validators:{
                            notEmpty:{
                                message:'请填写目标'
                            }
                        }
                    },
                }
            });

            $("#submit-btn").click(function () {
                var $form = $("#post-form");

                $form.bootstrapValidator('validate');
                if ($form.data('bootstrapValidator').isValid()) {
                    $form.submit();
                }
            });

            $('#faker_target').blur(function () {
                var typename = $('#select-type').val();
                if (typename != "goods"){
                    var input_val = $(this).val();
                    $("#target").val(input_val);
                }
            });

            befor_check();

            $("#select-type").bind("change",function(){
                $("#faker_target").val("");
                befor_check();
                $("#post-form").data('bootstrapValidator').resetForm();
                $("#post-form").bootstrapValidator('validate');
            });
        });

        function befor_check() {
            var typename = $("#select-type").val();
            if (typename == "goods"){
                $('#http').hide();
                $('#list_box2').show();
                $("#faker_target").attr("readonly",true);
            }else {
                $('#http').show();
                $('#list_box2').hide();
                $("#faker_target").removeAttr("readonly");
            }
        }
    </script>
@endsection