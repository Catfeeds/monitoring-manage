@extends('admin::layouts.main')

@section('content')
    @include('admin::search.grades-grades')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">年级管理</h3>

                    <div class="btn-group pull-right">
                        <a href=""
                           data-toggle="modal"
                           data-target="#create-modal"
                           title="新增年级" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;新增
                        </a>
                    </div>
                    @include('admin::widgets.filter-btn-group', ['resetUrl' => route('admin::grades.index')])
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>年级名称</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($grades as $grade)
                            <tr>
                                <td>{{ $grade->name }}</td>
                                <td>{{ $grade->created_at }}</td>
                                <td>
                                    <a href=""
                                       data-action="{{ route('admin::grades.update', $grade->id) }}"
                                       data-toggle="modal"
                                       data-target="#edit-modal"
                                       style="padding:3px 6px;"
                                       title="编辑年级" class="btn btn-info btn-sm grid-row-edit" data-id="{{ $grade->id }}" onclick="edit(this)" role="button">
                                        <i class="fa fa-edit"></i> 编辑
                                    </a>
                                    <a href="javascript:void(0);" data-id="{{ $grade->id }}" style="padding:3px 6px;" class="btn btn-danger btn-sm grid-row-delete" role="button">
                                        <i class="fa fa-trash"></i> 删除
                                    </a>&nbsp;
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
                                <h4 class="modal-title">新增年级</h4>
                            </div>
                            <form id="post-create-form" action="{{ route('admin::grades.store') }}" method="post">
                                {{ csrf_field() }}
                                <div class="modal-body">
                                    <div class="form">
                                        <div class="form-group">
                                            <label>年级名称</label>
                                            <input type="text" class="form-control" id="name" name="name" value="" placeholder="年级名称" />
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
                                            <label>年级名称</label>
                                            <input type="text" class="form-control" id="grade_name" name="grade_name" value="" placeholder="年级名称" />
                                            <input type="hidden" id="current_name" value="">
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
                    {{ $grades->appends(request()->all())->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::grades.index')])

    <script>
        var create_condition = {
            live: 'enable',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                name: {
                    validators: {
                        notEmpty: {
                            message: '请输入年级名称'
                        },
                        stringLength: {
                            max: 20,
                            message: '年级名称长度不能超过20个字符'
                        },
                        remote: {
                            url: "{{ route('admin::grades.checkName') }}",
                            message: '年级名称已存在',
                            delay: 200,
                            type: 'get',
                        }
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
                grade_name: {
                    validators: {
                        notEmpty: {
                            message: '请输入年级名称'
                        },
                        stringLength: {
                            max: 20,
                            message: '年级名称长度不能超过20个字符'
                        },
                        remote: {
                            url: "{{ route('admin::grades.checkName') }}",
                            message: '该年级名称已存在',
                            delay: 200,
                            type: 'get',
                            data: {
                                current_name: function () {
                                    return $('#current_name').val()
                                }
                            }
                        },
                    }
                },
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
            var current_name = $('#grade_name').val();
            var grade_name = $('#current_name').val();

            if(grade_name == current_name) {
                edit_form.bootstrapValidator('enableFieldValidators', 'grade_name', false);
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
            $('#grade_name').val($(obj).parents('tr:first').children('td:first').text());
            $('#current_name').val($(obj).parents('tr:first').children('td:first').text());
            $('#post-edit-form').attr('action', action);
        }

        $("#filter-modal .submit").click(function () {
            $("#filter-modal").modal('toggle');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });
    </script>
@endsection