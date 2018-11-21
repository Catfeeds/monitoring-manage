@extends('admin::layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">查看反馈内容</h3>
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            <a href="{{ route('admin::feedbacks.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a>
                        </div> <div class="btn-group pull-right" style="margin-right: 10px">
                            <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                        </div>
                    </div>
                </div>
                <form id="post-form" class="form-horizontal" action="£" method="post" enctype="multipart/form-data" pjax-container>

                    <div class="box-body">
                        <div class="fields-group">





                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">反馈内容</label>
                                <div class="col-sm-8">

                                    <textarea name="content" id="content" cols="100" rows="20" >{{$feedback->content}}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">反馈图片</label>
                                <div class="col-sm-8">
                                    @foreach($feedback->covers as $cover)
                                       <img src="{{$cover->path}}" width="100" height="100"/>
                                    @endforeach
                                </div>
                            </div>



                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="btn-group pull-right">
                            <button type="submit" id="submit-btn" class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin'></i> 完成">完成</button>
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


            $("#submit-btn").click(function () {
                event.preventDefault();
                history.back();
            })
        });
    </script>
@endsection