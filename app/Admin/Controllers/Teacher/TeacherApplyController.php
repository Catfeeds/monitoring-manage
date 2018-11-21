<?php

namespace app\Admin\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tanmo\Admin\Facades\Admin;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;
use App\Models\School;
use App\Models\TeacherApply;
use Tanmo\Admin\Models\Administrator;
use App\Models\Teacher;
use App\Api\Controllers\SendMessage;
use Illuminate\Support\Facades\DB;

/**
 * @module 园丁账号审核
 *
 * Class TeacherApplyController
 * @package app\Admin\Controllers\Teacher
 */
class TeacherApplyController extends Controller
{
    /**
     * @permission 园丁账号审核-待审核页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function applying()
    {
        $header = '园丁账号申请列表-待审核';

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

        $teacherApplies = (new TeacherApply())->orderBy('created_at', 'desc')->where([['school_id','=',$school_id],['status',0]])->paginate(10);

        return view('admin::teachers.teacherApplies',compact('header','teacherApplies'));
    }

    /**
     * @permission 园丁账号审核-已审核页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function finish()
    {
        $header = '园丁账号审核列表-已审核';

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

        $teacherApplies = (new TeacherApply())->orderBy('created_at', 'desc')->where('school_id','=',$school_id)->whereIn('status',[1,2])->paginate(10);

        return view('admin::teachers.teacherApplies-finish',compact('header','teacherApplies'));
    }

    /**
     * @permission 园丁账号审核-同意
     *
     * @param TeacherApply $teacherApply
     * @return \Illuminate\Http\JsonResponse
     */
    public function agreed(TeacherApply $teacherApply)
    {
        if(Admin::user()->can('operation',$teacherApply)) {
            return  DB::transaction(function () use ($teacherApply) {
                $administrator = new Administrator();

                $administrator->username =  $teacherApply->tel;
                $administrator->password =  $teacherApply->password;
                $administrator->name =  $teacherApply->name;
                $administrator->avatar = $teacherApply->avatar;
                $administrator->role = $teacherApply->class_id;
                $administrator->school_id = $teacherApply->school_id;

                $res = $administrator->save();
                if(!$res) {
                    return response()->json(['status' => 0, 'message' => '该教师管理员账号已存在！']);
                }
                $administrator->roles()->attach(array_filter(['3']));

                $teacher = new Teacher();
                $teacher->name = $teacherApply->name;
                $teacher->tel = $teacherApply->tel;
                $teacher->sex = $teacherApply->sex;
                $teacher->password = $teacherApply->password;
                $teacher->school_id = $teacherApply->school_id;
                $teacher->admin_id =  $administrator->id;
                $teacher->avatar = $teacherApply->avatar;
                $teacher->save();

                $teacher->collective()->attach($teacherApply->class_id);

                $teacherApply->status = 1;
                $res = $teacherApply->save();
                if(!$res) {
                    return response()->json(['status' => 0, 'message' => '该教师园丁账号已存在！']);
                }

                $sendMessage = new SendMessage();
                $rsg = $sendMessage->applySuccess($teacherApply->tel);
                if($rsg) {
                    return response()->json(['status' => 1, 'message' => '已同意，通知短信已发送！']);
                }

                return response()->json(['status' => 1, 'message' => '已同意,通知短信发送失败！']);
            });
        }
        else {
            //session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return response()->json(['status' => 0, 'message' => '当前用户权限不足']);
        }
    }

    /**
     * @permission 园丁账号审核-拒绝
     *
     * @param TeacherApply $teacherApply
     * @return \Illuminate\Http\JsonResponse
     */
    public function refused(TeacherApply $teacherApply)
    {
        if(Admin::user()->can('operation',$teacherApply)) {
            $teacherApply->status = 2;
            $teacherApply->save();

            return response()->json(['status' => 1, 'message' => '已拒绝']);
        }
        else {
            //session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return response()->json(['status' => 0, 'message' => '当前用户权限不足']);
        }
    }

    /**
     * @permission 删除已审核的园丁账号申请
     *
     * @param TeacherApply $teacherApply
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(TeacherApply $teacherApply)
    {
        if(Admin::user()->can('operation',$teacherApply)) {
            $teacherApply->delete();
            return response()->json(['status' => 1, 'message' => '成功']);
        }
        else {
            //session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return response()->json(['status' => 0, 'message' => '当前用户权限不足']);
        }
    }
}