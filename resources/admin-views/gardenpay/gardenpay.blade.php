@extends('admin::layouts.main')

@section('content')

    @include('admin::search.gardenpays-gardenpays')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">园所缴费</h3>

                    <div class="btn-group pull-right">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            <a href="" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#filter-modal"><i class="fa fa-filter"></i>&nbsp;&nbsp;选择班级</a>
                            <a href="{{ route('admin::gardenpays.index')}}" class="btn btn-sm btn-facebook"><i class="fa fa-undo"></i>&nbsp;&nbsp;撤销</a>
                        </div>
                    </div>
                </div>

               @if(!isset($cannt))
                <form id="post-form" class="form-horizontal" action="{{ route('admin::gardenpays.store') }}" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group">
                            <label for="avatar" class="col-sm-2 control-label">收款人</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="name"  id="name" @if(isset($gardenpay))value="{{$gardenpay->name}}"@endif>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="avatar" class="col-sm-2 control-label">银行卡账户：</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="account"  id="account" @if(isset($gardenpay))value="{{$gardenpay->account}}"@endif>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="avatar" class="col-sm-2 control-label">收款二维码</label>
                            <div class="col-sm-8">
                                <input type="file" class="qrcode" name="qrcode"  id="qrcode">
                            </div>
                        </div>
                        <input name="class_id" hidden type="text" value="{{request('class_id')}}">

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
                   @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::gardenpays.index')])
    <script>
        @if(isset($gardenpay))
        var urls='{{$gardenpay->qrcode}}';
        @else
        var urls='';
        @endif
        $("input.qrcode").fileinput({
            "overwriteInitial": false,
            "initialPreviewAsData": true,
            "browseLabel": "浏览",
            "showRemove": false,
            "showUpload": false,
            initialPreview: urls,
            "allowedFileTypes": [
                "image"
            ]
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
                name:{
                    validators:{
                        notEmpty:{
                            message: '收款人不能为空'
                        }
                    }
                },
                account:{
                    validators:{
                        notEmpty:{
                            message: '收款账户不能为空'
                        },
                        numeric:{
                            message:'收款账户不正确'
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









    </script>
@endsection