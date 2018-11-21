@extends('admin::layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">编辑</h3>
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            <a href="{{ route('admin::charges.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a>
                        </div> <div class="btn-group pull-right" style="margin-right: 10px">
                            <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                        </div>
                    </div>
                </div>
                <form id="post-form" class="form-horizontal" action="{{ route('admin::charges.update',$charge->id) }}" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    {{method_field('PUT')}}
                    <div class="box-body">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">收费价格</label>
                            <div class="col-sm-3">
                                <input type="text" id="money" name="money" value="{{$charge->money}}" class="form-control title" placeholder="输入 收费价格">
                            </div>
                            <label for="title" class="col-sm-2 control-label">收费时长</label>
                            <div class="col-sm-3">
                                <input type="text" id="time" name="time" value="{{$charge->time}}" class="form-control title" placeholder="输入 收费时长">
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


            ///
            $("#post-form").bootstrapValidator({
                live: 'enable',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    money:{
                        validators:{
                            notEmpty:{
                                message: '请输入收费价格'
                            },
                            regexp: {
                                regexp: /^\d+\.\d{0,2}$/,
                                message: '请保留两位小数'
                            }
                        }
                    },
                    time:{
                        validators:{
                            notEmpty:{
                                message: '请输入产品周期'
                            },
                            numeric: {
                                message: '只能输入数字'
                            }
                        }
                    }
                }
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