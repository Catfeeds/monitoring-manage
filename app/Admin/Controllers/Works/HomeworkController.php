<?php

namespace App\Admin\Controllers\Works;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Tanmo\Admin\Facades\Admin;
use App\Models\School;
use App\Models\Homework;
use App\Models\Collective;

/**
 * @module 作业提醒
 *
 * Class HomeworkController
 * @package App\Admin\Controllers\Works
 */
class HomeworkController extends Controller
{
    /**
     * @permission 作业列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
//        $searcher = Search::build(function (Searcher $searcher) {
//            $searcher->like('title');
//        });
        $header = '作业列表';
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

//        if(Admin::user()->isRole('teacher')) {
//            $notics =(new MessageNotic())->search($searcher)->where([['school_id',$school_id],['scope',3]])->whereIn(Admin::user()->class_id,)->latest()->paginate(10);
//        }
//        else {
        $homeworks =(new Homework())->where('school_id',$school_id)->latest()->paginate(10);
//        }
        return view('admin::works.homeworks-homeworks',compact('homeworks','header'));
    }

    /**
     * @permission 新增作业-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $header = '新作业';

        if(Admin::user()->isAdmin()){
            $school_id = getShowSchoolId();
        }else {
            $school_id = Admin::user()->school_id;
        }

        $collectives = Collective::where('school_id',$school_id)->latest()->with('grade')->get();
        return view('admin::works.homeworks-create',compact('header','collectives'));
    }

    /**
     * @permission 新增作业
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(Admin::user()->isAdmin()){
            $school_id = getShowSchoolId();
        }else {
            $school_id = Admin::user()->school_id;
        }

        $homework = new Homework();
        $homework->title = $request->get('title');
        $homework->content = $request->get('content');
        $homework->admin_id = Admin::user()->id;
        $homework->class_id = $request->get('collective');
        $homework->school_id = $school_id;
        $homework->end_at = $request->get('end_at');

        $homework->save();

        return redirect()->route('admin::homeworks.index');
    }

    /**
     * @permission 编辑作业-页面
     *
     * @param Homework $homework
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit(Homework $homework)
    {
        if(Admin::user()->isAdmin()){
            $school_id = getShowSchoolId();
        }else {
            $school_id = Admin::user()->school_id;
        }

        if(Admin::user()->can('operation',$homework)) {
            $header = '编辑通知';

            $collectives = Collective::where('school_id',$school_id)->latest()->get();
            return view('admin::works.homeworks-edit', compact('homework','collectives','header'));
        }
        else {
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::homeworks.index');
        }
    }

    /**
     * @permission 编辑作业
     *
     * @param Request $request
     * @param Homework $homework
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,Homework $homework)
    {
        if(Admin::user()->can('operation',$homework)) {
            $homework->title = $request->get('title');
            $homework->content = $request->get('content');
            $homework->end_at = $request->get('end_at');
            $homework->save();
            return redirect()->route('admin::homeworks.index');
        }
        else {
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::homeworks.index');
        }
    }

    /**
     * @permission 删除作业
     *
     * @param Homework $homework
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Homework $homework)
    {
        if(Admin::user()->can('operation',$homework)) {
            $homework->delete();

            return response()->json(['status' => 1, 'message' => '成功']);
        }
        else {
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::homework-s.index');
        }
    }

}
