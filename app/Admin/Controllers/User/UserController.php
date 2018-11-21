<?php

namespace App\Admin\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Tanmo\Admin\Facades\Admin;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;


/**
 * @module 家长管理
 *
 * Class HomeController
 * @package App\Admin\Controllers
 */
class UserController extends Controller{
    /**
     * @permission 家长列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $header = '家长列表';

        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('name');
        });

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

        if(Admin::user()->class_id) {
//            $users = (new User())->search($searcher)->orderBy('created_at', 'desc')->where('school_id', '=', $school_id)->where('class_id','=',Admin::user()->class_id)->paginate(10);
            $users = (new School())->find($school_id)->parents;
        }
        else{
            $users = (new School())->find($school_id)->parents;
//            $users = (new User())->search($searcher)->orderBy('created_at', 'desc')->where('school_id', '=', $school_id)->paginate(10);
        }

        return view('admin::users.user',compact('users','header'));

    }


    /**
     * @permission 家长删除
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function destroy(School $school){
        $administrators = (new Administrator())->where('school_id','=',$school->id)->get();
        foreach ($administrators as $administrator){
            $administrator->state = $administrator->state?0:1;
            $administrator->save();
        }
        $school->state =  $school->state?0:1;
        $school->save();
        return response()->json(['status' => 1, 'message' => '更改成功']);
    }

    public function show(School $school){
        if(!Admin::user()->isAdmin())
            return;

        $ip = getIp();
        Redis::set($ip."show_school_id",$school->id);
        $school_name ="当前家长为".$school->name;
        session()->flash('success',collect(['title'=>['家长选择成功'],'message'=>[$school_name ]]));
        if(!IS_AJAX) {
            return redirect()->route('admin::schools.index');
        }

    }
}