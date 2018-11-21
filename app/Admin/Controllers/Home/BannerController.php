<?php

namespace app\Admin\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Classify;
use App\Models\Press;
use Illuminate\Http\Request;
use Tanmo\Admin\Facades\Admin;
use App\Models\School;
/**
 * @module 轮播图
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
     * @permission 轮播图列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $header = '轮播图列表';
        if(Admin::user()->isAdmin()){
            $school_id = getShowSchoolId();
            if($school_id ) {
                $school = (new School())->where('id', '=', $school_id)->first();
                $header = $school->name . "--" . $header;
            }
            else{
                return view('admin::errors.no_school');
            }
        }else {
            $school_id = Admin::user()->school_id;
        }
        $link_types = $this->link_types;
        $banners = Banner::where('school_id',$school_id)->get();
        return view('admin::home.banners', compact('banners','header','link_types'));
    }

    /**
     * @permission 新增轮播图-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $header = '新增轮播图';
        $link_types = $this->link_types;

        $school_id = getSchoolId();
        if (is_object($school_id)){
            $school_id = 0;
        }
        $classifies = Classify::where('school_id',$school_id)->get();
        return view('admin::home.banner-create', compact('link_types','header','classifies'));
    }

    /**
     * @permission 新增轮播图
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $banner = new Banner();
        if(Admin::user()->isAdmin()){
            $school_id = getShowSchoolId();
            $banner->school_id = $school_id;
        }
        else {
            $banner->school_id = Admin::user()->school_id;
        }

        $banner->order = $request->order;
        $banner->status = $request->status;


        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('banner', 'public');
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

        return redirect()->route('admin::banners.index');
    }

    /**
     * @permission 修改轮播图-页面
     *
     * @param Banner $banner
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Banner $banner)
    {
        if(Admin::user()->can('operation',$banner)) {
            $header = '编辑轮播图';

            if($banner->link_type == 'url'){
                $str = $banner->link;
                $needle1 = "http://";
                $needle2 = "https://";
                $result = str_replace($needle1,'',$str);
                $result = str_replace($needle2,'',$result);
                $banner->target_2 =$result;
            }
            $link_types = $this->link_types;
            return view('admin::home.banner-edit', compact('banner', 'link_types','title','header'));
        }
        else {
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::banners.index');
        }
    }

    /**
     *
     * @param Banner $banner
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Banner $banner, Request $request)
    {
        if(Admin::user()->can('operation',$banner)) {
            $banner->order = $request->order;
            $banner->status = $request->status;

            if ($request->hasFile('banner')) {
                $path = $request->file('banner')->store('banner', 'public');
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

            return redirect()->route('admin::banners.index');
        }
        else {
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::banners.index');
        }
    }

    /**
     * @permission 删除轮播图
     *
     * @param Banner $banner
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Banner $banner)
    {
        if(Admin::user()->can('operation',$banner)) {
            $banner->delete();

            return response()->json(['status' => 1, 'message' => '成功']);
        }
        else {
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::banners.index');
        }
    }
}