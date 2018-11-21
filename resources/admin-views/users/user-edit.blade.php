@extends('admin::layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">编辑</h3>
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            <a href="{{ route('admin::users.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a>
                        </div> <div class="btn-group pull-right" style="margin-right: 10px">
                            <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                        </div>
                    </div>
                </div>
                <form id="post-form" class="form-horizontal" action="{{ route('admin::users.update', $user->id) }}" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="box-body">
                        <div class="form-group">
                            <label for="order" class="col-sm-2 control-label">家长名称</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" id="name" name="name" disabled value="{{ $user->name }}" class="form-control order" placeholder="输入 学校名称">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="avatar" class="col-sm-2 control-label">头像</label>
                            <div class="col-sm-8">
                                <input type="file" class="avatar" name="avatar" id="avatar"  accept="image/*">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="order" class="col-sm-2 control-label">用户昵称</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" id="name" name="name" disabled value="{{ $user->nickname }}" class="form-control order" placeholder="输入 学校名称">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="order" class="col-sm-2 control-label">联系方式</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" id="name" name="name" disabled value="{{ $user->phone }}" class="form-control order" placeholder="输入 学校名称">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="order" class="col-sm-2 control-label">性别</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" id="name" name="name" disabled value="{{ $user->sex }}" class="form-control order" placeholder="输入 学校名称">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="order" class="col-sm-2 control-label">状态</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" id="name" name="name" disabled value="{{ $user->status }}" class="form-control order" placeholder="输入 学校名称">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="order" class="col-sm-2 control-label">等级</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" id="name" name="name" disabled value="{{ $user->grades }}" class="form-control order" placeholder="输入 学校名称">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="box-footer">
                        {{--<div class="btn-group pull-left">--}}
                            {{--<button type="reset" class="btn btn-warning">重置</button>--}}
                        {{--</div>--}}
                        {{--<div class="btn-group pull-right">--}}
                            {{--<button type="submit" id="submit-btn" class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin'></i> 提交">提交</button>--}}
                        {{--</div>--}}
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

            ///
            $("#post-form").bootstrapValidator({
                live: 'enable',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    fields: {
                        name:{
                            validators:{
                                notEmpty:{
                                    message: '请输入学校名称'
                                }
                            }
                        }
                    }
                }
            });

            var previewConfigs = [];
            var urls = [];
            var j = {};
            j.downloadUrl = "{{  $user->avatar}}";
            j.key = "{{ $user->id }}";
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

                $form.bootstrapValidator('validate');
                if ($form.data('bootstrapValidator').isValid()) {
                    $form.submit();
                }
            });




        });


    </script>
@endsection