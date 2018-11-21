@extends('admin::layouts.main')

@section('content')
    @include('admin::search.collectives-collectives')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">班级管理</h3>

                    <div class="btn-group pull-right">
                        <a href=""
                           data-toggle="modal"
                           data-target="#create-modal"
                           title="新增班级" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;新增
                        </a>
                    </div>

                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::collectives.index')])
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>班级名称</th>
                            <th>编号</th>
                            <th>所属年级</th>
                            <th>学生数量</th>
                            <th>开通视频在线数量</th>
                            <th>备注</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($collectives as $collective)
                            <tr>
                                <td>{{ $collective->name }}</td>
                                <td>{{ $collective->sn }}</td>
                                <td>{{ $collective->grade->name }}</td>
                                <td>{{ $collective->students->count() }}</td>
                                <td>{{ $collective->parents->count() }}</td>
                                <td>{{ $collective->remark }}</td>
                                <td>{{ $collective->created_at }}</td>
                                <td>
                                    <a href=""
                                       data-action="{{ route('admin::collectives.update', $collective->id) }}"
                                       data-toggle="modal"
                                       data-target="#edit-modal"
                                       style="padding:3px 6px;"
                                       title="编辑年级" class="btn btn-info btn-sm grid-row-edit" data-grade="{{ $collective->grade_id }}" data-id="{{ $collective->id }}" onclick="edit(this)" role="button">
                                        <i class="fa fa-edit"></i> 编辑
                                    </a>
                                    <a href="#"  data-id="{{ $collective->id }}" style="padding:3px 6px;" data-toggle="modal" data-target="#qrCode-modal" data-qrcode="{{ $collective->qrcode }}" class="btn btn-success btn-sm qr-code" role="button">
                                        <i class="fa fa-table"></i> 二维码
                                    </a>
                                    <a href="#" data-id="{{ $collective->id }}" style="padding:3px 6px;" class="btn btn-danger btn-sm grid-row-delete" role="button">
                                        <i class="fa fa-trash"></i> 删除
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="modal fade" id="create-modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">新增班级</h4>
                            </div>
                            <form id="post-create-form" action="{{ route('admin::collectives.store') }}" method="post">
                                {{ csrf_field() }}
                                <div class="modal-body">
                                    <div class="form">
                                        <div class="form-group">
                                            <label>选择年级</label>
                                            <select class="form-control grade" style="width: 100%;" name="grade_id" data-placeholder="选择年级"  >
                                                <option value="">请选择</option>
                                                @foreach($grades as $grade)
                                                    <option value="{{ $grade->id }}" >{{ $grade->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>班级名称</label>
                                            <input type="text" class="form-control" id="name" name="name" value="" placeholder="班级名称" />
                                        </div>
                                        <div class="form-group">
                                            <label>备注</label>
                                            <textarea class="form-control" rows="5" id="remark" name="remark" placeholder="备注" ></textarea>
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

                <div class="modal fade" id="qrCode-modal">
                    <input id="download-url" type="hidden" value="">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">班级二维码<small style="color: deepskyblue"> (*右键保存到本地)</small></h4>
                            </div>

                            <div style="text-align: center " class="modal-body" >
                                <img id="qrCode_show" src="">
                            </div>
                            <div class="modal-footer">
                                {{--<button id="download-qr" class="btn btn-warning center-block">保存到本地</button>--}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="edit-modal">
                    <div class="modal-dialog">
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
                                            <label>所属年级</label>
                                            <select class="form-control grade" id="grade_id" style="width: 100%;" name="grade_id" data-placeholder="选择年级"  >
                                                @foreach($grades as $grade)
                                                    <option value="{{ $grade->id }}" >{{ $grade->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>班级名称</label>
                                            <input type="text" class="form-control" id="edit_name" name="name" value="" placeholder="班级名称" />
                                            <input type="hidden" id="current_name" value="">
                                        </div>
                                        <div class="form-group">
                                            <label>备注</label>
                                            <textarea class="form-control" rows="5" id="edit_remark" name="remark" placeholder="备注" ></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="reset" id="reset-edit" class="btn btn-warning pull-left">清空</button>
                                    <input type="button" onclick="return false;" class="btn btn-primary" data-id="" id="deliver-btn" value="提交"  data-loading-text="<i class='fa fa-spinner fa-spin'></i> 提交">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    {{ $collectives->appends(request()->all())->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::collectives.index')])

    <script>
        var create_condition = {
            live: 'enable',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                grade_id: {
                    validators: {
                        notEmpty: {
                            message: '请选择年级'
                        }
                    }
                },
                name: {
                    validators: {
                        notEmpty: {
                            message: '请输入班级名称'
                        },
                        stringLength: {
                            max: 20,
                            message: '班级名称长度不能超过20个字符'
                        },
                        remote: {
                            url: "{{ route('admin::collectives.checkName') }}",
                            message: '班级名称已存在',
                            delay: 200,
                            type: 'get',
                        }
                    }
                },
                remark: {
                    validators: {
                        stringLength: {
                            max: 50,
                            message: '备注长度不能超过50个字符'
                        },
                    }
                }
            }
        };

        var edit_condition = {
            live: 'enable',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                grade_id: {
                    validators: {
                        notEmpty: {
                            message: '请选择年级'
                        }
                    }
                },
                name: {
                    validators: {
                        notEmpty: {
                            message: '请输入班级名称'
                        },
                        stringLength: {
                            max: 20,
                            message: '年级名称长度不能超过20个字符'
                        },
                        remote: {
                            url: "{{ route('admin::collectives.checkName') }}",
                            message: '该班级名称已存在',
                            delay: 200,
                            type: 'get',
                            data: {
                                current_name: function () {
                                    return $('#current_name').val()
                                },
                                grade_id: function () {
                                    return $('#grade_id').val()
                                }
                            }
                        },
                    }
                },
                remark: {
                    validators: {
                        stringLength: {
                            max: 50,
                            message: '备注长度不能超过50个字符'
                        },
                    }
                }
            }
        };

        var create_form = $("#post-create-form");
        create_form.bootstrapValidator(create_condition);

        $('#create-modal').on('hidden.bs.modal',function () {
            create_form.bootstrapValidator('destroy');
            create_form.data('bootstrapValidator',null);
            create_form.bootstrapValidator(create_condition);
        });

        $('#reset-create').on('click',function () {
            create_form.bootstrapValidator('destroy');
            create_form.data('bootstrapValidator',null);
            create_form.bootstrapValidator(create_condition);
        });

        var edit_form = $("#post-edit-form");

        $('#edit-modal').on('hidden.bs.modal',function () {
            edit_form.bootstrapValidator('destroy');
            edit_form.data('bootstrapValidator',null);
            edit_form.bootstrapValidator(edit_condition);
        });

        $('#reset-edit').on('click',function () {
            edit_form.bootstrapValidator('destroy');
            edit_form.data('bootstrapValidator',null);
            edit_form.bootstrapValidator(edit_condition);
        });

        edit_form.bootstrapValidator(edit_condition);

        $('#deliver-btn').click(function () {
            var current_name = $('#edit_name').val();
            var name = $('#current_name').val();

            if(name == current_name) {
                edit_form.bootstrapValidator('enableFieldValidators', 'name', false);
            }
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
                            swal({
                                title:res.message,
                                type:'success',
                                timer:1500
                            });
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

        $(".grade").select2({
            "allowClear": true
        });

        function edit(obj) {
            var action = $(obj).data('action');
            $('#edit_name').val($(obj).parents('tr:first').children('td:first').text());
            $('#current_name').val($(obj).parents('tr:first').children('td:first').text());
            $('#edit_remark').val($(obj).parents('tr:first').children('td:eq(4)').text());
            $("#grade_id > option").each(function () {
                //console.log($(obj).data('grade'));
                if($(this).val() == $(obj).data('grade')) {
                    $(this).prop('selected',true);
                    return;
                }
            });
            $(".grade").select2({
                "allowClear": true
            });
            $('#post-edit-form').attr('action', action);
        }

        $(".qr-code").click(function () {
            var qrcode = $(this).data('qrcode');
            $("#qrCode_show").attr('src',qrcode);
        });


        $("#filter-modal .submit").click(function () {
            $("#filter-modal").modal('toggle');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });
    </script>
@endsection