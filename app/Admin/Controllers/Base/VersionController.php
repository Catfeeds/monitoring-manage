<?php

namespace app\Admin\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Models\Version;
use App\Models\VerGard;
use Illuminate\Http\Request;
use Tanmo\Admin\Facades\Admin;
use Illuminate\Support\Facades\Storage;

/**
 * @module App版本设置
 *
 * Class VersionController
 * @package app\Admin\Controllers\Base
 */
class VersionController extends Controller
{
    /**
     * @permission 版本设置页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $header = '版本设置';

        $version = Version::where('status','android')->first();
        return view('admin::base.versions-edit',compact('version','verGard','header'));
    }

    /**
     * @permission 编辑版本信息
     *
     * @param Request $request
     * @param Version $version
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,Version $version)
    {
        $version->version_no = $request->get('version_no');
        $version->vergrad_no = $request->get('vergrad_no');

        if($request->file('download_file')) {
            if($version->download_url) {
                $url = public_path(Storage::url($version->download_url));
                if(file_exists($url)) {
                    unlink($url);
                }
                $version->download_url = '';
            }
            $str = 'uu' . date('YmdHis') . Admin::user()->id . rand(100, 999);
            $suffix = strtolower($request->file('download_file')->getClientOriginalExtension());
            $filename = $str.'.'.$suffix;
            $path = $request->file('download_file')->storeAs('app_version', $filename, 'public');
            $version->download_url = $path;
        }
        if($request->file('grad_url')) {
            if($version->grad_url) {
                unlink(public_path(Storage::url($version->grad_url)));
                $version->grad_url = '';
            }
            $str = 'gg' . date('YmdHis') . Admin::user()->id . rand(100, 999);
            $suffix = strtolower($request->file('grad_url')->getClientOriginalExtension());
            $filename = $str.'.'.$suffix;
            $path = $request->file('grad_url')->storeAs('app_version', $filename, 'public');
            $version->grad_url = $path;
        }
        $version->save();
        return json_encode(['status' => 1,'message' => '提交成功！']);
    }
}