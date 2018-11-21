<?php

namespace app\Admin\Controllers\Campus;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;
use Tanmo\Admin\Facades\Admin;
use App\Models\School;

use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;
/**
 * @module 年级
 *
 * Class GradeController
 * @package app\Admin\Controllers\Campus
 */
class GradeController extends Controller
{
    /**
     * @permission 年级列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('name');
        });

        $header = '年级列表';
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
        $grades = (new Grade())->search($searcher)->where('school_id',$school_id)->latest()->paginate(10);
        return view('admin::campus.grades-grades',compact('grades','header'));
    }

    /**
     * @permission 新增年级
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $grade = new Grade();
        $grade->name = $request->get('name');
        if(Admin::user()->isAdmin()) {
            $school_id = getShowSchoolId();
            $grade->school_id = $school_id;
        }
        else {
            $grade->school_id = Admin::user()->school_id;
        }
        $grade->save();
        return redirect()->route('admin::grades.index');
    }

    /**
     * @permission 修改年级
     *
     * @param Request $request
     * @param Grade $grade
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,Grade $grade)
    {
        if(Admin::user()->can('operation',$grade)) {
            $grade->name = $request->get('grade_name');
            $grade->save();
            return response()->json(['status' => 1, 'message' => '修改成功']);
        }
        else {
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::grades.index');
        }
    }

    /**
     * @return string
     */
    public function checkName()
    {
        $name = request()->get('name');
        if(!$name) {
            $name =  request()->get('grade_name');
        }
        if(Admin::user()->isAdmin()) {
            $school_id = getShowSchoolId();
        }
        else {
            $school_id = Admin::user()->school_id;
        }
        $current_name = request()->get('current_name');

        if($current_name || $current_name == '0') {
            if( $current_name == $name) {
                return '{"valid":true}';
            }
        }

        $grade = Grade::where([['school_id','=',$school_id],['name','=',$name]])->first();
        if($grade) {
            return '{"valid":false}';
        }
        return '{"valid":true}';
    }

    /**
     * @permission 删除年级
     *
     * @param Grade $grade
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Grade $grade)
    {
        if(Admin::user()->can('operation',$grade)) {
            if($grade->collectives->count()>0)
            {
                return response()->json(['status' => 0, 'message' => '删除失败!该年段下存在班级']);
            }
            $grade->delete();
            return response()->json(['status' => 1, 'message' => '成功']);
        }
        else {
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::grades.index');
        }
    }

    public function getCollectives(Grade $grade)
    {
        return response()->json(['status' =>1,'collectives' => $grade->collectives]);
    }
}