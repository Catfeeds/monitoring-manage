@extends('admin::layouts.main')

@section('content')

    @include('admin::search.spaces-spaces')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">班级空间发布列表</h3>
                    <div class="btn-group pull-right">
                        <a href="{{ route('admin::spaces.create')}}" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;发布
                        </a>
                    </div>
                    <div class="btn-group pull-right">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            <a href="" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#filter-modal"><i class="fa fa-filter"></i>&nbsp;&nbsp;选择班级</a>
                            <a href="{{ route('admin::spaces.index')}}" class="btn btn-sm btn-facebook"><i class="fa fa-undo"></i>&nbsp;&nbsp;撤销</a>
                        </div>
                    </div>
                </div>


                @if(isset($spaces))
                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>发布人</th>
                            <th>发布文件</th>
                            <th>发布时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($spaces as $space)
                            <tr>
                                <td> {{ $space->admin->name }}</td>
                                {{--<td>--}}
                                    {{--<a class="btn btn-xs btn-default grid-expand collapsed" data-inserted="0" data-key="{{ $space->id }}" data-toggle="collapse" data-target="#grid-collapse-{{ $space->id }}" aria-expanded="false">--}}
                                           {{--<i class="fa fa-caret-right"></i> 详情--}}
                                    {{--</a>--}}
                                    {{--<template class="grid-expand-{{ $space->id }}">--}}
                                        {{--<div id="grid-collapse-{{ $space->id }}" class="collapse">--}}
                                            {{--<div class="box box-primary box-solid">--}}
                                                {{--<div class="box-header with-border">--}}
                                                    {{--<h3 class="box-title">上传图片</h3>--}}
                                                    {{--<div class="box-tools pull-right">--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                                {{--<div class="box-body" style="display: block;">--}}
                                                    {{--<table class="table">--}}
                                                        {{--<thead>--}}
                                                        {{--<tr>--}}
                                                            {{--<th>图片</th>--}}
                                                        {{--</tr>--}}
                                                        {{--</thead>--}}
                                                        {{--<tbody>--}}
                                                        {{--@if($space->image)--}}
                                                        {{--@foreach(explode(',',$space->image) as $image)--}}
                                                            {{--<tr>--}}
                                                                {{--<td>--}}
                                                                    {{--<div class="layer-photos-demo">--}}
                                                                        {{--<img src="{{\Illuminate\Support\Facades\Storage::disk('public')->url($image)}}"  height="70" width="70" class="" >--}}
                                                                    {{--</div>--}}
                                                                {{--</td>--}}
                                                            {{--</tr>--}}
                                                        {{--@endforeach--}}
                                                        {{--@endif--}}
                                                        {{--</tbody>--}}
                                                    {{--</table>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</template>--}}
                                {{--</td>--}}
                                <td>@if($space->file)<a id="down" href="#" links="{{\Illuminate\Support\Facades\Storage::disk('public')->url($space->file)}}" download="{{path_name($space->file)}}"><span class="label label-success">{{path_name($space->file)}}</span></a>@else<span class="label label-danger">未上传文件</span>@endif</td>
                                <td> {{ $space->created_at}}</td>
                                <td>
                                    <a href="javascript:void(0);" data-id="{{ $space->id }}" class="btn btn-danger btn-sm grid-row-delete">
                                        <i class="fa fa-trash"></i>删除
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $spaces->links('admin::widgets.pagination') }}
                </div>
                @endif

            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::spaces.index')])
    <script>
        $("#down").click(function(){
            window.location.href=this.attr('links');

        });

        layui.use('layer',function () {
            var layer = layui.layer;
            layer.photos({
                photos:'.layer-photos-demo',
                anim:0
            });
        });





        $('.grid-expand').on('click', function () {
            if ($(this).data('inserted') == '0') {
                var key = $(this).data('key');
                var row = $(this).closest('tr');
                var html = $('template.grid-expand-'+key).html();

                row.after("<tr><td colspan='"+row.find('td').length+"' style='padding:0 !important; border:0px;'>"+html+"</td></tr>");

                $(this).data('inserted', 1);
            }
            layui.use('layer',function () {
                var layer = layui.layer;
                layer.photos({
                    photos:'.layer-photos-demo',
                    anim:0
                });
            });

            $("i", this).toggleClass("fa-caret-right fa-caret-down");
        });





    </script>
@endsection