@extends('admin::layouts.main')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            <a href="{{ route('admin::schools.index') }}" class="btn btn-sm btn-default"><i class="fa fa-home"></i>&nbsp;学校列表</a>
                        </div>
                    </div>
                </div>
                <div class="jumbotron">
                    <h3 style="color:red;">未选择学校，请选择学校</h3>
                </div>
            </div>
        </div>
    </div>
@endsection
