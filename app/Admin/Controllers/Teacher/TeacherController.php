<?php

namespace App\Admin\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Collective;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Tanmo\Admin\Facades\Admin;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;
use App\Models\School;
use Tanmo\Admin\Models\Administrator;
use Tanmo\Admin\Requests\AdministratorRequest;
use App\Models\Grade;
/**
 * @module 老师管理
 *
 * Class HomeController
 * @package App\Admin\Controllers
 */
class TeacherController extends Controller
{
    /**
     * @permission 老师列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('name');
        });
        $header = '老师列表';

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

        $teachers = (new Teacher())->search($searcher)->orderBy('created_at', 'desc')->where('school_id','=',$school_id)->paginate(10);

        return view('admin::teachers.teacher',compact('header','teachers'));
    }

    /**
     * @permission 老师创建-页面
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
        return view('admin::teachers.teacher-create',compact('grades'));
    }


    /**
     * @permission 老师创建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(AdministratorRequest $request){

        if((new Administrator())->where('username','=', $request->get('username'))->count()>0){
            session()->flash('error',collect(['title'=>['表单错误'],'message'=>['当前账号已存在']]));
            return redirect()->route('admin::teachers.create');
        }
        $administrator = new Administrator();

        $administrator->username =  $request->get('username');
        $administrator->password =  bcrypt($request->get('password'));
        $administrator->name =  $request->get('name');
        $administrator->avatar = $this->getAvatar($request);
        $administrator->role = Administrator::TEACHER;



        $teacher = new Teacher();
        $teacher->name= $request->get('tname');
        $teacher->sex = $request->get('sex');
        $teacher->is_head = $request->get('is_head');
        $teacher->note = $request->get('note');
        $teacher->tel = $request->get('tel');

        if(Admin::user()->isAdmin()){
            $school_id = getShowSchoolId();
            $teacher->school_id = $school_id;
            $administrator->school_id = $school_id;
        }
        else {
            $teacher->school_id = Admin::user()->school_id;
            $administrator->school_id = $school_id;
        }

        $administrator->save();
        if ($administrator) {
            $roleIds = array_filter(['3']);
            $administrator->roles()->sync($roleIds);
        }

        $teacher->admin_id =  $administrator->id;
        if ($request->hasFile('tavatar')) {
            $path = $request->file('tavatar')->store('teacher', 'public');
            $teacher->avatar = $path;
        }
        $teacher->save();
        $teacher->collective()->sync($request->get('class_id'));


        return redirect()->route('admin::teachers.index');
    }

    /**
     * @permission 老师编辑-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Teacher $teacher){
        if(Admin::user()->can('operation',$teacher)) {


            if(Admin::user()->isAdmin()){

                $school_id = getShowSchoolId();
            }else {
                $school_id = Admin::user()->school_id;
            }

            $grades = (new Grade())->where('school_id','=',$school_id)->get();
            $classes = $teacher->collective;
            return view('admin::teachers.teacher-edit',compact('teacher','grades','classes'));
        }
        else{
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::teachers.index');
        }

    }

    /**
     * @permission 老师编辑
     * @param Teacher $teacher
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Teacher $teacher,Request $request){
        if(Admin::user()->can('operation',$teacher)) {
            $teacher->name = $request->get('name');
            $teacher->sex = $request->get('sex');
            $teacher->is_head = $request->get('is_head');
            $teacher->note = $request->get('note');
            $teacher->tel = $request->get('tel');
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('teacher', 'public');
                $teacher->avatar = $path;
            }
            $teacher->save();
            $teacher->collective()->sync($request->get('class_id'));
            return redirect()->route('admin::teachers.index');
        }
        else{
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::teachers.index');
        }
    }

    /**
     * @permission 老师删除
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function destroy(Teacher $teacher){
        if(Admin::user()->can('operation',$teacher)) {
            $teacher->delete();
            return response()->json(['status' => 1, 'message' => '设置成功']);
        }
        else{
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::teachers.index');
        }
    }


    protected function getAvatar(Request $request)
    {
        if ($request->hasFile('avatar')) {
            return $avatar = $request->file('avatar')->store('avatar', config('admin.upload.disk.avatar'));
        }

        return null;
    }

    /**
     * @permission 离职教师列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(){

            $header = '离职教师列表';

            $searcher = Search::build(function (Searcher $searcher) {
                $searcher->like('name');
            });

            if (Admin::user()->isAdmin()) {
                $school_id = getShowSchoolId();
                if ($school_id) {
                    $school = (new School())->where('id', '=', $school_id)->first();
                    $header = $school->name . "--" . $header;
                } else {
                    return view('admin::errors.no_school');
                }
            } else {
                $school_id = Admin::user()->school_id;
            }
            $teachers = (new Teacher())->search($searcher)->orderBy('created_at', 'desc')->where('school_id', '=', $school_id)->onlyTrashed()->paginate(10);
            return view('admin::teachers.awayteacher', compact('teachers', 'header'));
    }

    public function reduction($teacher){
        $teachers = Teacher::withTrashed()->find($teacher);
        if(Admin::user()->can('operation',$teachers)) {

            if (Admin::user()->isAdmin()) {
                $school_id = getShowSchoolId();
            } else {
                $school_id = Admin::user()->school_id;
            }

            $teachers->restore();

            return redirect()->route('admin::teachers.index');
        }
        else{
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::teachers.index');
        }
    }




}