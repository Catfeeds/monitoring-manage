<?php

namespace app\Admin\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Models\PlatBanner;
use App\Models\Classify;
use App\Models\Press;
use Illuminate\Http\Request;
/**
 * @module 平台轮播图
 *
 * Class BannerController
 * @package App\Admin\Controllers\Home
 */
class BannerController extends Controller
{
    /**
     * @var array
     */
    protected $link_types = [
        'url' => '外部链接',
        'article' => '内部文章',
    ];

    /**
     * @permission 平台-轮播图列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $header = '平台-轮播图列表';

        $link_types = $this->link_types;
        $banners = PlatBanner::latest()->get();
        return view('admin::base.banners', compact('banners','header','link_types'));
    }

    /**
     * @permission 平台-新增轮播图-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $header = '平台-新增轮播图';

        $link_types = $this->link_types;
        $classifies = Classify::where('school_id',0)->get();
        return view('admin::base.banner-create', compact('link_types','header','classifies'));
    }

    /**
     * @permission 平台-新增轮播图
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $banner = new PlatBanner();

        $banner->order = $request->order;
        $banner->status = $request->status;


        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('plat_banner', 'public');
            $banner->cover = $path;
        }

        $http_val = $request->http;
        $link_type = $request->link_type;
        $link = $request->link;

        if($link_type == 'url'){
            switch ($http_val){
                case 1:
                    $url_pre = 'http://';
                    break;
                case 2:
                    $url_pre = 'https://';
                    break;
                default:
                    $url_pre = '';
            }
        }else{
            $url_pre = '';
        }

        $banner->link = $url_pre.$link;
        switch ($request->link_type){
            case 'url':
                $banner->link_type = '0';
                break;
            case 'article':
                $banner->link_type = '1';
                break;
            case 'goods':
                $banner->link_type = '2';
                break;
        }
        $banner->save();

        return redirect()->route('admin::platBanners.index');
    }

    /**
     * @permission 平台-修改轮播图-页面
     *
     * @param PlatBanner $banner
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(PlatBanner $platBanner)
    {
        $header = '编辑轮播图';

        if($platBanner->link_type == 'url'){
            $str = $platBanner->link;
            $needle1 = "http://";
            $needle2 = "https://";
            $result = str_replace($needle1,'',$str);
            $result = str_replace($needle2,'',$result);
            $platBanner->target_2 =$result;
        }
        $link_types = $this->link_types;
        return view('admin::base.banner-edit', compact('platBanner', 'link_types','title','header'));
    }

    /**
     * @permission 平台-修改轮播图
     *
     * @param PlatBanner $banner
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PlatBanner $platBanner, Request $request)
    {
        $platBanner->order = $request->order;
        $platBanner->status = $request->status;

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('banner', 'public');
            $platBanner->cover = $path;
        }

        $http_val = $request->http;
        $link_type = $request->link_type;
        $link = $request->link;

        if($link_type == 'url'){
            switch ($http_val){
                case 1:
                    $url_pre = 'http://';
                    break;
                case 2:
                    $url_pre = 'https://';
                    break;
                default:
                    $url_pre = '';
            }
            }else{
                $url_pre = '';
            }
            $platBanner->link = $url_pre.$link;
            switch ($request->link_type){
                case 'url':
                    $platBanner->link_type = '0';
                    break;
                case 'article':
                    $platBanner->link_type = '1';
                    break;
                case 'goods':
                    $platBanner->link_type = '2';
                    break;
            }
            $platBanner->save();

            return redirect()->route('admin::platBanners.index');
    }

    /**
     * @permission 平台-删除轮播图
     *
     * @param PlatBanner $banner
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(PlatBanner $platBanner)
    {
        $platBanner->delete();
        return response()->json(['status' => 1, 'message' => '成功']);

    }
}