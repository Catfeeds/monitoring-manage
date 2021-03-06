@extends('admin::layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">编辑</h3>
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            {{--<a href="{{ route('admin::banners.home') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a>--}}
                            <a class="btn btn-sm btn-default form-history-back"><i class="fa "></i>&nbsp;列表</a>
                        </div> <div class="btn-group pull-right" style="margin-right: 10px">
                            <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                        </div>
                    </div>
                </div>
                @inject('bannerPresenter', "App\Admin\Presenters\BannerPresenter")
                <form id="post-form" class="form-horizontal" action="{{ route('admin::banners.update', $banner->id) }}" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="box-body">
                        <div class="form-group">
                            <label for="order" class="col-sm-2 control-label">排序</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="text" id="order" name="order" value="{{ $banner->order }}" class="form-control order" placeholder="输入 排序">
                                </div>
                            </div>
                        </div>

                        <div class="fields-group">
                            <div class="form-group">
                                <label for="banner" class="col-sm-2 control-label">轮播图</label>
                                <div class="col-sm-8">
                                    <input type="file" class="banner" name="banner" id="banner" data-initial-preview="{{ $banner->cover }}" data-initial-caption="{{ basename($banner->cover) }}" accept="image/*">
                                    <span class="help-block">建议尺寸：640*350，请将轮播图尺寸大小保持一致</span>
                                </div>
                            </div>
                        </div>

                        <div class="fields-group">
                            <div class="form-group">
                                <label for="type" class="col-sm-2 control-label">链接类型</label>
                                <div class="col-sm-8">

                                    <select class="form-control type" style="width: 100%;" id="select-type" name="link_type" data-placeholder="选择 类型"  >
                                        @foreach($link_types as $k => $type)

                                            <option value="{{ $k }}" @if($banner->link_type == $k) selected @endif>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="target" class="col-sm-2 control-label">链接目标</label>
                            <div class="col-sm-8">
                                <div class="input-group" style="width: 100%">
                                    <div id="http" class="input-group-btn" style="display: none;">
                                        <select name="http" class="btn btn-default" style="background-color: white;">
                                            <option value="1">http://</option>
                                            <option value="2">https://</option>
                                        </select>
                                    </div>
                                    <input type="hidden" id="target" name="link" value="{{ $banner->link }}" class="form-control target" placeholder="输入 目标">
                                    <input type="text" id="faker_target" name="faker_target"  @if($banner->link_type == 'url') value = "{{ $banner->target_2 }}" @else value = "您选择的是：{!! $bannerPresenter->showTitle($banner) !!}" @endif class="form-control target" placeholder="输入 目标">
                                </div>
                                {{--<span class="help-block">根据选择的类型填写本字段内容，选择“链接”需填写完整链接地址</span>--}}
                            </div>
                        </div>

                        <div class="" id="art_list_box" style="width: 60%;margin: auto;display: none">
                            <div class="input-group">
                                <input type="text" class="form-control" id="art_search_key" style="height: 34px">
                                <span class="input-group-btn">
                                    <button id="search_btn" target="art" class="btn btn-default search_btn"  type="button" value="搜索">
                                    搜索
                                    </button>
                                </span>
                            </div>
                            <div id="list-view" style="overflow-y:auto;height: 250px">
                                <table class="table">
                                    <tbody id="art_list">
                                    <tr>
                                        <th>标题</th>
                                        <th>更新时间</th>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div id="art-btn">
                            </div>
                        </div>
                        <div class="" id="goods_list_box" style="width: 60%;margin: auto;display: none">
                            <div class="input-group">
                                <input type="text" class="form-control" id="good_search_key" style="height: 34px">
                                <span class="input-group-btn">
                                    <button id="search_btn" target="goods" class="btn btn-default search_btn"  type="button" value="搜索">
                                    搜索
                                    </button>
                                </span>
                            </div>
                            <div id="list-view" style="overflow-y:auto;height: 250px">
                                <table class="table">
                                    <tbody id="goods_list">
                                    <tr>
                                        <th>型号</th>
                                        <th>编号</th>
                                        <th>图片</th>
                                        <th>所属商品</th>
                                        <th>品牌</th>
                                        <th>标签</th>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div id="goods_btn">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status" class="col-sm-2 control-label">启用</label>
                            <div class="col-sm-8">
                                <input type="checkbox" class="status la_checkbox" @if($banner->status) checked @endif/>
                                <input type="hidden" class="status" name="status" value="{{ $banner->status }}"/>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        {{--<div class="btn-group pull-left">--}}
                        {{--<button type="reset" id="reset-btn" class="btn btn-warning">重置</button>--}}
                        {{--</div>--}}
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

            $(".order").bootstrapNumber({
                'upClass': 'success',
                'downClass': 'primary',
                'center': true
            });

            $(".banner").fileinput({
                overwriteInitial: false,
                initialPreviewAsData: true,
                browseLabel: "浏览",
                showRemove: false,
                showUpload: false,
                allowedFileTypes: [
                    "image"
                ]
            });

            $(".type").select2({
                "allowClear": true
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

            $("#post-form").bootstrapValidator({
                live: 'enable',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    type:{
                        validators:{
                            notEmpty:{
                                message: '请选择类型'
                            }
                        }
                    },
                    target:{
                        validators:{
                            notEmpty:{
                                message:'请填写目标'
                            },
                        }
                    },
                    faker_target:{
                        validators:{
                            notEmpty:{
                                message:'请填写目标'
                            }
                        }
                    },
                }
            });

            $("#submit-btn").click(function () {
                var $form = $("#post-form");
                $form.bootstrapValidator('validate');
                if ($form.data('bootstrapValidator').isValid()) {
                    $form.submit();
                }
            });

            $('#reset-btn').click(function () {
                validate();
            })

            $('body').on('click','.item-tr',function () {
                var id = $(this).attr('val_id');
                var title = $(this).children("td[type='val_title']").html();
                $("#target").val(id);
                $("#faker_target").val("您选择的是："+title);
                validate();
            });

            befor_check();

            $("#select-type").bind("change",function(){
                $("#faker_target").val("");
                befor_check();
                $("#post-form").data('bootstrapValidator').resetForm();
                $("#post-form").bootstrapValidator('validate');
            });

            $('#find-list').on('click',function () {
                $('#list_box2').toggle();
            })

            $('.search_btn').on('click',function () {
                creatlist(this);
            });

            $('body').on('click','#page',function () {
                creatlist(this);
            });

            $('#faker_target').blur(function () {
                var typename = $('#select-type').val();
                if (typename != "goods"){
                    var input_val = $(this).val();
                    $("#target").val(input_val);
                }
            });

        });

        function validate() {
            $("#post-form").data('bootstrapValidator').resetForm();
            $("#post-form").bootstrapValidator('validate');
        }

        function befor_check() {

            var typename = $("#select-type").val();
            if (typename == "goods"){
                $('#goods_list_box').show();
                $('#http').hide();
                $('#art_list_box').hide();
                $("#faker_target").attr("readonly",true);
            } else if (typename == "article"){
                $('#art_list_box').show();
                $('#http').hide();
                $('#goods_list_box').hide();
                $("#faker_target").attr("readonly",true);
            }else {
                $('#http').show();
                $('#art_list_box').hide();
                $('#goods_list_box').hide();
                $("#faker_target").removeAttr("readonly");
            }
        }
        function creatlist(th) {

            var url = '';
            var flag = $(th).attr('target');
            if( flag == 'art'){
                url = "/admin/press/search";
                var key = $('#art_search_key').val();
                var flag_list = $('#art_list');
                var flag_page = $('#art-btn');
            }else{
                url = "/itemSearch";
                var key = $('#good_search_key').val();
                var flag_list = $('#goods_list');
                var flag_page = $('#goods_btn');
            }

            var html = '';
            var html2 = '';
            var page = $(th).attr('pageid');
            $.ajax({
                url:url,
                type:"post",
                data:{
                    _token:LA.token,
                    keyword:key,
                    page:page?page:1,
                },
                success:function (data) {
                    $('.item-tr').remove();
                    $('#list-btn-main').remove();
                    var items = data.data;
                    if(flag == 'art'){
                        $(items).each(function (index,value) {
                            html +=  "<tr val_id='"+value['id']+"' class='item-tr' style=\"cursor: pointer;\">"
                            html +=  "<td type='val_title'>"+value['title']+"</td>";
                            html +=  "<td>"+value['created_at']+"</td></tr>";
                        })
                    }else{
                        $(items).each(function (index,value) {
                            html +=  "<tr val_id='"+value['item_id']+"' class='item-tr' style=\"cursor: pointer;\">";
                            html +=  "<td type='val_title'>"+value['models_title']+"</td>";
                            html +=  "<td>"+value['item_sn']+"</td>";
                            html +=  "<td><img src='"+value['covers']+"' width='50' class='img-circle' height='50'/></td>";
                            html +=  "<td>"+value['item_title']+"</td>";
                            html +=  "<td>"+value['brand_title']+"</td>";
                            html +=  "<td><span class=\"label label-primary\">"+value['label_title']+"</span></td></tr>";
                        })
                    }

                    html2 +="<div id='list-btn-main' class=\"box-footer\"><ul class=\"pagination pagination-sm no-margin pull-right\">";
                    for (var i=1;i<=data.last_page;i++)
                    {
                        html2 += "<li style=\"cursor: pointer;\"><span id=\"page\" pageid='"+i+"'>"+i+"</span></li>";
                    }
                    html2 += "</ul></div>";

                    flag_list.append(html);
                    flag_page.append(html2);
                }
            })
        }
    </script>
@endsection