@extends('admin::layouts.main')

@section('content')
    @include('admin::search.helps-helps')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">帮助中心</h3>

                    <div class="btn-group pull-right">
                        <a href=""
                           data-toggle="modal"
                           data-target="#create-modal"
                           title="新增问答" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;新增
                        </a>
                    </div>
                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::helps.index')])
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>问题标题</th>
                            <th>是否启用</th>
                            <th>所属App端</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($helps as $help)
                            <tr>
                                <td>{{ $help->id }}</td>
                                <td>{{ $help->title }}</td>
                                <td>{{ $help->status? '启用' : '禁用' }}</td>
                                <td>{{ $help->scope==1? '家长端' : '园丁端' }}</td>
                                <td>{{ $help->created_at }}</td>
                                <td>
                                    <a href=""
                                       data-action="{{ route('admin::helps.update', $help->id) }}"
                                       data-toggle="modal"
                                       data-target="#edit-modal"
                                       style="padding:3px 6px;"
                                       title="编辑问答" class="btn btn-info btn-sm grid-row-edit" data-scope="{{ $help->scope }}" data-status="{{ $help->status }}" data-content="{{ $help->content }}" data-id="{{ $help->id }}" onclick="edit(this)" role="button">
                                        <i class="fa fa-edit"></i> 编辑
                                    </a>
                                    <a href="javascript:void(0);" data-id="{{ $help->id }}" style="padding:3px 6px;" class="btn btn-danger btn-sm grid-row-delete" role="button">
                                        <i class="fa fa-trash"></i> 删除
                                    </a>&nbsp;
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="modal fade" id="create-modal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">新增问答</h4>
                            </div>
                            <form id="post-create-form" action="{{ route('admin::helps.store') }}" method="post">
                                {{ csrf_field() }}
                                <div class="modal-body">
                                    <div class="form">
                                        <div class="form-group">
                                            <label>问</label>
                                            <input type="text" class="form-control"  name="title" value="" placeholder="问题标题" />
                                        </div>
                                        <div class="form-group">
                                            <label>答</label>
                                            <textarea class="form-control" id="editor1" rows="8" name="content" placeholder="回答"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>所属App端</label><br>
                                            <div class="input-group">
                                                <lable class="radio-inline">
                                                    <input type="radio" name="scope" value="1" checked/>家长端
                                                </lable>
                                                <lable class="radio-inline">
                                                    <input type="radio" name="scope" value="2"  />园丁端
                                                </lable>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>是否启用</label><br>
                                            <input type="checkbox" class="status la_checkbox" checked/>
                                            <input type="hidden" class="status" name="status" value="1"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="reset" id="reset-create" class="btn btn-warning pull-left">清空</button>
                                    <button type="submit" class="btn btn-primary" data-id="" data-loading-text="<i class='fa fa-spinner fa-spin'></i> 提交">提交</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="edit-modal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">编辑</h4>
                            </div>
                            <form id="post-edit-form" action="" method="post">
                                {{ csrf_field() }}
                                <div class="modal-body">
                                    <div class="form">
                                        <div class="form-group">
                                            <label>问</label>
                                            <input type="text" class="form-control" id="title" name="title" value="" placeholder="问题标题" />
                                        </div>
                                        <div class="form-group">
                                            <label>答</label>
                                            <textarea class="form-control" id="editor2" rows="8" name="content" placeholder="回答"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>所属App端</label><br>
                                            <div class="input-group">
                                                <lable class="radio-inline">
                                                    <input type="radio" name="scope" value="1" checked/>家长端
                                                </lable>
                                                <lable class="radio-inline">
                                                    <input type="radio" name="scope" value="2"  />园丁端
                                                </lable>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>是否启用</label><br>
                                            <input type="checkbox" id="check_box" class="status la_checkbox"/>
                                            <input type="hidden" id="status" class="status" name="status" value=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="reset" id="reset-edit" class="btn btn-warning pull-left">清空</button>
                                    <button type="submit" onclick="return false;" class="btn btn-primary" data-id="" id="deliver-btn"  data-loading-text="<i class='fa fa-spinner fa-spin'></i> 提交">提交</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    {{ $helps->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::helps.index')])

    <script>
        var condition = {
            live: 'enable',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                title: {
                    validators: {
                        notEmpty: {
                            message: '请输入问题标题'
                        },
                        stringLength: {
                            max: 50,
                            message: '问题标题长度不能超过50个字符'
                        },
                    }
                },
                content: {
                    validators: {
                        notEmpty: {
                            message: '回答不能为空'
                        },
                    }
                }
            }
        };

        var editor1 = new Simditor({
            textarea: $('#editor1'),
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

        var create_form = $("#post-create-form");
        create_form.bootstrapValidator(condition);

        $('#create-modal').on('hidden.bs.modal',function () {
            create_form.bootstrapValidator('destroy');
            create_form.data('bootstrapValidator',null);
            create_form.bootstrapValidator(condition);
        });

        $('#reset-create').on('click',function () {
            create_form.bootstrapValidator('destroy');
            create_form.data('bootstrapValidator',null);
            create_form.bootstrapValidator(condition);
        });

        var edit_form = $("#post-edit-form");

        $('#edit-modal').on('hidden.bs.modal',function () {
            edit_form.bootstrapValidator('destroy');
            edit_form.data('bootstrapValidator',null);
            edit_form.bootstrapValidator(condition);
        });

        $('#reset-edit').on('click',function () {
            edit_form.bootstrapValidator('destroy');
            edit_form.data('bootstrapValidator',null);
            edit_form.bootstrapValidator(condition);
        });

        edit_form.bootstrapValidator(condition);

        $('#deliver-btn').click(function () {

            edit_form.bootstrapValidator('validate');
            if (edit_form.data('bootstrapValidator').isValid()) {
                $.ajax({
                    url: edit_form.attr('action'),
                    type: 'PUT',
                    data: edit_form.serialize(),
                    dataType: 'json',
                    success: function (res) {
                        if (res.status) {
                            $.pjax.reload('#pjax-container');
                            swal(res.message, '', 'success');
                        }
                        else {
                            swal(res.message, '', 'error');
                            edit_form[0].reset();
                            $(".has-feedback").removeClass('has-success').removeClass('has-error');
                            $(".form-control-feedback").hide();
                        }
                        $("#edit-modal").modal('toggle');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    }
                });
            }
            else {
                return false;
            }
        });

        function edit(obj) {
            var action = $(obj).data('action');
            $('#title').val($(obj).parents('tr:first').children('td:eq(1)').text());
            editor2.setValue($(obj).data('content'));
            $('#status').val($(obj).data('status'));
            $("input:radio[name='scope'][value="+$(obj).data('scope')+"]").prop('checked','true');
            if($(obj).data('status')) {
                $('.status.la_checkbox').bootstrapSwitch('state', true);
            }
            $('#post-edit-form').attr('action', action);
        }

        $("#filter-modal .submit").click(function () {
            $("#filter-modal").modal('toggle');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });
    </script>
@endsection