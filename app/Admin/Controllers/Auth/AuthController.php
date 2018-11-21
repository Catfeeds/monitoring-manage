<?php

namespace App\Admin\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth;
use App\Models\Collective;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tanmo\Admin\Facades\Admin;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;

/**
 * @module 入园申请管理
 *
 * Class ItemController
 * @package App\Admin\Controllers\Items
 */
class AuthController extends Controller{

    /**
     * @permission 入园申请列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $school_id = getSchoolId();

        if (is_object($school_id)){
            return $school_id;
        }

        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('user.name','user_name');
            $searcher->like('operators.name','operator_name');
            $searcher->equal('class_id');
            $searcher->equal('status');
            $searcher->gte('created_at','start');
            $searcher->lte('created_at','end');
        });

        if(Admin::user()->role) {
            $teacher = Teacher::where('admin_id',Admin::user()->id)->first();
            $classe = $teacher->collective;
            $ids=array();
            foreach ($classe as $k=>$v){
                $ids[$k]=$v->id;
            }
            $auths = (new Auth())->search($searcher)->with(['user','collective'])->whereIn('class_id',$ids)->orderByDesc('created_at')->paginate(10);
        }else{
            $classe = Collective::where('school_id',$school_id)->get();
            $ids=array();
            foreach ($classe as $k=>$v){
                $ids[$k]=$v->id;
            }
            $auths = (new Auth())->search($searcher)->with(['user','collective'])->whereIn('class_id',$ids)->orderByDesc('created_at')->paginate(10);
        }

        foreach ($auths as $key => $val){
            $auths[$key]->info = json_decode($val->info);
//            $auths[$key]->schoolNumInfo = (new Student())->where('school_num',$val->student_num)->with('collective')->select('name','class_id')->first();
        }
        return view('admin::auth.auth',compact('auths','classe'));


    }


    /**
     * @permission 拒绝申请
     *
     * @param Auth $auth
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View3
     */
    public function refuse(Auth $auth,Request $request)
    {

        if ($auth->status != Auth::APPLYING) return response()->json(['status' => 0, 'message' => '该申请状态已修改']);
        $auth->status = Auth::REFUSE;
        $auth->refusal_reason = $request->reason;
        $auth->operator = $user = Admin::user()->id;
        $auth->save();
        return response()->json(['status' => 1, 'message' => '已拒绝']);
    }

    /**
     * @permission 同意入园
     *
     * @param Auth $auth
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View3
     */
    public function agree(Auth $auth)
    {
        if ($auth->status != Auth::APPLYING) return response()->json(['status' => 0, 'message' => '该申请状态已修改']);


        $student = Student::find( $auth->student_id);
        $class = Collective::find($auth->class_id);

        $grade_id = $class->grade->id;
        $school_id = $class->school->id;

        $info = json_decode($auth->info,1);


        $student->school_id = $school_id;
        $student->grade_id = $grade_id;
        $student->class_id = $class->id;

        $student->save();

//        ///查询是否已经有过该关系
//        $record = DB::table('student_parent')->where('student_id',$student->id)->where('parent_id',$user_id)->first();
//        if ($record) return response()->json(['status' => 0, 'message' => '已绑定过该学生']);


        foreach ($info as $key => $val){
            $user = User::find($val['user_id']);
//            $role = $val['relation'];
//            $student->parents()->attach($user->id,['role'=>$role]);

            if ($user->grades == User::VISITOR){
                $user->grades = User::PARENT;
                $user->save();
            }

            $user->schools()->syncWithoutDetaching($school_id);
            $user->collectives()->syncWithoutDetaching($class->id);
        }

        $auth->operator = Admin::user()->id;

        $auth->status = Auth::AGREE;
        $auth->save();

        return response()->json(['status' => 1, 'message' => '已同意']);
    }


}