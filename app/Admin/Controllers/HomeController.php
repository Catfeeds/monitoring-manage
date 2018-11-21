<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Tanmo\Admin\Controllers\Main;
use Tanmo\Admin\Facades\Admin;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * @module 管理后台
 *
 * Class HomeController
 * @package App\Admin\Controllers
 */
class HomeController extends Controller
{
    /**
     * @permission 主页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if(Admin::user()->isAdmin()){
            $ip = getIp();
            Redis::set($ip."show_school_id",0);
            setSchools();
        }
        return Main::envs();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setSchoolId()
    {

        if(Admin::user()->isAdmin()){
            $school_id = request()->get('school_id');
            $ip = getIp();
            Redis::set($ip."show_school_id",$school_id);
        }
        return redirect()->route('admin::schools.show',getShowSchoolId());
    }
}