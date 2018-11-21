@extends('admin::layouts.main')

@section('content')
    <style>
        td { text-align: center; }
        th { text-align: center; }
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">平台轮播图列表</h3>

                    <div class="btn-group pull-right">
                        <a href="{{ route('admin::platBanners.create') }}" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;新增
                        </a>
                    </div>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>序号</th>
                            <th>图片</th>
                            <th>目标类型</th>
                            <th>跳转目标</th>
                            <th>排序</th>
                            <th>是否启用</th>
                            <th>操作</th>
                        </tr>
                        @inject('bannerPresenter', "App\Admin\Presenters\BannerPresenter")
                        @foreach($banners as $key => $banner)
                            <tr>
                                <td style="vertical-align:middle;">{{ $key+1 }}</td>
                                <td style="vertical-align:middle;"><img src="{{ $banner->cover }}"  width='150' height='70' class='img-thumbnail'  alt=""></td>
                                <td style="vertical-align:middle;">{{ $link_types[$banner->link_type] }}</td>
                                <td style="vertical-align:middle;">{!! $bannerPresenter->showTitle($banner) !!}</td>
                                <td style="vertical-align:middle;">{{ $banner->order }}</td>
                                <td style="vertical-align:middle;">{{ $banner->status ? '是' : '否' }}</td>
                                <td style="vertical-align:middle;">
                                    <a href="{{ route('admin::platBanners.edit', $banner->id) }}" style="padding:3px 6px;" class="btn btn-info btn-sm" role="button">
                                        <i class="fa fa-edit"></i> 编辑
                                    </a>
                                    <a href="javascript:void(0);" data-id="{{ $banner->id }}" data-url="{{ route('admin::platBanners.destroy',$banner->id) }}" style="padding:3px 6px;" class="btn btn-danger btn-sm grid-row-delete" role="button">
                                        <i class="fa fa-trash"></i> 删除
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    @include('admin::js.grid-row-delete', ['url' => route('admin::platBanners.index')])
@endsection