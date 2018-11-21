<?php

namespace app\Admin\Controllers\Works;

use App\Http\Controllers\Controller;
use App\Models\MessageNotic;
use Illuminate\Http\Request;
use Tanmo\Admin\Facades\Admin;
use App\Models\School;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;
use App\Models\Collective;

/**
 * @module 通知
 *
 * Class MessageNoticController
 * @package app\Admin\Controllers\Works
 */
class MessageNoticController extends Controller
{
    /**
     * @permission 通知列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('title');
        });
        $header = '通知列表';
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
            $notics =(new MessageNotic())->search($searcher)->where('school_id',$school_id)->latest()->paginate(10);
//        }
        return view('admin::works.notics-notics',compact('notics','header'));
    }

    /**
     * @permission 新增通知-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $header = '新通知';

        if(Admin::user()->isAdmin()){
            $school_id = getShowSchoolId();
        }else {
            $school_id = Admin::user()->school_id;
        }

        $collectives = Collective::where('school_id',$school_id)->latest()->with('grade')->get();
        return view('admin::works.notics-create',compact('header','collectives'));
    }

    /**
     * @permission 新增通知
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

        $notic = new MessageNotic();
        $notic->title = $request->get('title');
        $notic->content = $request->get('content');
        $notic->admin_id = Admin::user()->id;
        $notic->scope = $request->get('scope');
        $collectives = $request->get('collectives');
        $notic->school_id = $school_id;
        if($request->get('scope') == 3) {
            if(!$collectives) {
                $collectives = Collective::where('school_id',$school_id)->pluck('id')->toArray();
            }
            $notic->collection_ids = $collectives;
        }
        $notic->save();

        return redirect()->route('admin::notics.index');
    }

    /**
     * @permission 编辑通知-页面
     *
     * @param MessageNotic $notic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     *
     */
    public function edit(MessageNotic $notic)
    {
        if(Admin::user()->can('operation',$notic)) {
            $header = '编辑通知';
            if($notic->scope == '班级') {
                if($notic->collection_ids) {
                    $collectives = Collective::whereIn('id',$notic->collection_ids)->latest()->with('grade')->get();
                }
                else {
                    $collectives = '所有班级';
                }
                return view('admin::works.notics-edit', compact('notic','collectives','header'));
            }
            else {
                return view('admin::works.notics-edit', compact('notic','header'));
            }
        }
        else {
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::notics.index');
        }
    }

    /**
     * @permission 编辑通知
     *
     * @param Request $request
     * @param MessageNotic $notic
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,MessageNotic $notic)
    {
        if(Admin::user()->can('operation',$notic)) {
            $notic->title = $request->get('title');
            $notic->content = $request->get('content');
            $notic->save();
            return redirect()->route('admin::notics.index');
        }
        else {
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::notics.index');
        }
    }

    /**
     * @permission 删除通知
     *
     * @param MessageNotic $notic
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(MessageNotic $notic)
    {
        if(Admin::user()->can('operation',$notic)) {
            $notic->delete();

            return response()->json(['status' => 1, 'message' => '成功']);
        }
        else {
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::notics.index');
        }
    }
}