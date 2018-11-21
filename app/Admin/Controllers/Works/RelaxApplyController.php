<?php

namespace app\Admin\Controllers\Works;

use App\Http\Controllers\Controller;
use App\Models\RelaxApply;
use Illuminate\Http\Request;
use Tanmo\Admin\Facades\Admin;
use App\Models\School;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;

/**
 * @module 请假申请
 *
 * Class RelaxApplyController
 * @package app\Admin\Controllers\Works
 */
class RelaxApplyController extends Controller
{
    /**
     * @permission 请假列表--待审核
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function applying()
    {
        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('parent.name','parent_name');
            $searcher->like('teacher.name','teacher_name');
        });

        $header = '请假列表-待审核';
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
        $start = request()->get('start');
        $end = request()->get('end');
        if(Admin::user()->role) {
            $data = getTeacherClassIds(Admin::user()->id);
            $relaxApplies = (new RelaxApply())->search($searcher)->where([['school_id',$school_id],['teacher_id',$data['id']]])->whereIn('class_id',$data['ids'])->FilterStatus([RelaxApply::APPLYING])->TimeInterval($start, $end)->with(['student.grade', 'student.collective', 'parent', 'teacher'])->paginate(10);
        }
        else {
            $relaxApplies = (new RelaxApply())->search($searcher)->where('school_id', $school_id)->FilterStatus([RelaxApply::APPLYING])->TimeInterval($start, $end)->with(['student.grade', 'student.collective', 'parent', 'teacher'])->paginate(10);
        }
        return view('admin::works.relaxApplies-relaxApplies',compact('relaxApplies','header'));
    }

    /**
     * @permission 请假列表--已完成
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function finish()
    {
        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('parent.name','parent_name');
            $searcher->like('teacher.name','teacher_name');
            $searcher->equal('status');
        });

        $header = '请假列表-已完成';
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

        $start = request()->get('start');
        $end = request()->get('end');
        if(Admin::user()->role) {
            $data = getTeacherClassIds(Admin::user()->id);
            $relaxApplies = (new RelaxApply())->search($searcher)->where([['school_id',$school_id],['teacher_id',$data['id']]])->whereIn('class_id',$data['ids'])->FilterStatus([RelaxApply::AGREED,RelaxApply::REFUSED,RelaxApply::CANCEL])->TimeInterval($start,$end)->with(['student.grade','student.collective','parent','teacher'])->paginate(10);
        }
        else {
            $relaxApplies = (new RelaxApply())->search($searcher)->where('school_id',$school_id)->FilterStatus([RelaxApply::AGREED,RelaxApply::REFUSED,RelaxApply::CANCEL])->TimeInterval($start,$end)->with(['student.grade','student.collective','parent','teacher'])->paginate(10);
        }
        return view('admin::works.relaxApplies-finish',compact('relaxApplies','header'));
    }

    /**
     * @permission 同意请假申请
     *
     * @param RelaxApply $relaxApply
     * @return \Illuminate\Http\JsonResponse
     */
    public function agreed(RelaxApply $relaxApply)
    {
        if(Admin::user()->can('operation',$relaxApply)) {
            $relaxApply->status = RelaxApply::AGREED;
            $relaxApply->save();

            return response()->json(['status' => 1, 'message' => '已同意']);
        }
        else {
            //session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return response()->json(['status' => 0, 'message' => '当前用户权限不足']);
        }
    }

    /**
     * @permission 拒绝请假申请
     *
     * @param RelaxApply $relaxApply
     * @return \Illuminate\Http\JsonResponse
     */
    public function refused(RelaxApply $relaxApply)
    {
        if(Admin::user()->can('operation',$relaxApply)) {
            $relaxApply->status = RelaxApply::REFUSED;
            $relaxApply->save();

            return response()->json(['status' => 1, 'message' => '已拒绝']);
        }
        else {
            //session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return response()->json(['status' => 0, 'message' => '当前用户权限不足']);
        }
    }

    /**
     * @permission 删除申请
     *
     * @param RelaxApply $relaxApply
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(RelaxApply $relaxApply)
    {
        if(Admin::user()->can('operation',$relaxApply)) {
            $relaxApply->delete();
            return response()->json(['status' => 1, 'message' => '成功']);
        }
        else {
            //session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return response()->json(['status' => 0, 'message' => '当前用户权限不足']);
        }
    }
}