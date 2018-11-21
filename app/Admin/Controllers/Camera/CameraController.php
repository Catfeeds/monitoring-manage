<?php

namespace App\Admin\Controllers\Camera;

use App\Http\Controllers\Controller;
use App\Models\Camera;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Tanmo\Admin\Models\Administrator;
use Tanmo\Admin\Facades\Admin;
use Tanmo\Admin\Requests\AdministratorRequest;
use App\Models\School;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;
use App\Models\Grade;
use App\Models\Collective;
/**
 * @module 硬件管理
 *
 * Class HomeController
 * @package App\Admin\Controllers
 */
class CameraController extends Controller
{
    /**
     * @permission 硬件列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $header = '硬件列表';
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

        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('uid');
        });
       $cameras = (new Camera())->search($searcher)->where('school_id','=',$school_id)->orderBy('created_at','desc')->paginate(10);

        return view('admin::cameras.camera',compact('cameras','header'));
    }

    /**
     * @permission 硬件创建-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){

        if(Admin::user()->isAdmin()){

            $school_id = getShowSchoolId();

        }else {
            $school_id = Admin::user()->school_id;
        }


        $grades = (new Grade())->where('school_id','=',$school_id)->get();
        return view('admin::cameras.camera-create',compact('grades'));
    }


    /**
     * @permission 硬件创建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(Request $request){
        $header = '硬件添加';
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
        $camera = new Camera();
        $camera->uid = $request->get('uid');
        $camera->class_id = $request->get('class_id');
        $camera->school_id = $school_id;
        $camera->area = $request->get('area');
        $camera->save();
        return redirect()->route('admin::cameras.index');
    }

    /**
     * @permission 硬件编辑
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Camera $camera){
        if(Admin::user()->can('operation',$camera)) {

            if(Admin::user()->isAdmin()){

                $school_id = getShowSchoolId();
            }else {
                $school_id = Admin::user()->school_id;
            }

            $grades = (new Grade())->where('school_id','=',$school_id)->get();
            $class = (new Collective())->where('id','=',$camera->class_id)->first();
            $classes = $class->grade->collectives;
            return view('admin::cameras.camera-edit', compact('camera','grades','classes'));
        }
        return redirect()->route('admin::cameras.index');
    }

    public function update(Request $request,Camera $camera){
        if(Admin::user()->can('operation',$camera)) {
            $camera->uid = $request->get('uid');
            $camera->class_id = $request->get('class_id');
            $camera->area = $request->get('area');
            $camera->save();
            return redirect()->route('admin::cameras.index');
        }
        return redirect()->route('admin::cameras.index');
    }

    /**
     * @permission 硬件删除
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function destroy(Camera $camera){
        if(Admin::user()->can('operation',$camera)) {
            $camera->delete();
            return response()->json(['status' => 1, 'message' => '删除成功']);
        }
        return redirect()->route('admin::cameras.index');
    }

}