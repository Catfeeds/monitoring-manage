<?php

namespace app\Admin\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;

/**
 * @module 关于我们
 *
 * Class AboutController
 * @package app\Admin\Controllers\Base
 */
class AboutController extends Controller
{
    /**
     * @permission 关于我们页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $header = '关于我们';

        $about = About::first();
        return view('admin::base.abouts-edit',compact('about','header'));
    }

    /**
     * @permission 编辑关于我们
     *
     * @param Request $request
     * @param About $about
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,About $about)
    {
        $about->content = $request->get('content');
        $about->save();

        return redirect()->route('admin::abouts.index');
    }
}