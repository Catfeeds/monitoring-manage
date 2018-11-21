<?php

namespace App\Admin\Controllers\Press;

use App\Http\Controllers\Controller;
use App\Models\Classify;
use App\Models\Press;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tanmo\Admin\Facades\Admin;
/**
 * @module 新闻内容管理
 *
 * Class HomeController
 * @package App\Admin\Controllers
 */
class PressController extends Controller
{
    
    /**
     * @permission 新闻列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $school_id=getSchoolId();
        if(is_object($school_id)) {
            $school_id=Classify::ADMIN_SCHOOL;
        }
        $press=DB::table('press')
            ->select('press.*')
            ->join('classifies','classifies.id','=','press.classify_id')
            ->where('classifies.school_id','=',$school_id)
            ->orderBy('press.created_at','desc')
            ->paginate(10);
        return view('admin::press.press',compact('press'));
    }
    /**
     * @permission 新闻创建-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        $school_id=getSchoolId();
        if(is_object($school_id)) {
            $school_id=Classify::ADMIN_SCHOOL;
        }
        $classifies=Classify::where('school_id',$school_id)->get();
        return view('admin::press.press-create',compact('classifies'));
    }

    /**
     * @permission 新闻创建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(Request $request){

        $school_id=getSchoolId();
        if(is_object($school_id)) {
            $school_id=Classify::ADMIN_SCHOOL;
        }
        $data = [
            'content'=>$request->get('content'),
            'classify_id'=>$request->get('classify_id'),
            'title'=>$request->get('title'),
            'banner'=>$request->file('banner')->store('banner','public'),
            'user_id'=> Admin::user()->id
        ];
        Press::create($data);
        return redirect()->route('admin::press.index');
    }

    /**
     * @permission 新闻编辑-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Press $press){
        if(Admin::user()->can('operation',$press)) {
            $school_id = getSchoolId();
            if(is_object($school_id)) {
                $school_id=Classify::ADMIN_SCHOOL;
            }
            $classifies = Classify::where('school_id', $school_id)->get();
            return view('admin::press.press-edit', compact('press', 'classifies'));
        }
        return redirect()->route('admin::press.index');
    }

    /**
     * @permission 分类更新
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Request $request,Press $press){
        if(Admin::user()->can('operation',$press)) {
            if($request->file('banner')) {
                $data = [
                    'classify_id' => $request->get('classify_id'),
                    'title' => $request->get('title'),
                    'banner' => $request->file('banner')->store('banner', 'public'),
                    'content' => $request->get('content'),
                ];
            }
            else{
                $data = [
                    'classify_id' => $request->get('classify_id'),
                    'title' => $request->get('title'),
                    'content' => $request->get('content'),
                ];
            }
            $press->update($data);
            return redirect()->route('admin::press.index');
        }
        return redirect()->route('admin::press.index');
    }

    /**
     * @permission 分类删除
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function destroy(Press $press){
        if(Admin::user()->can('operation',$press)) {
            $press->delete();
            return response()->json(['status' => 1, 'message' => '删除成功']);
        }
        return redirect()->route('admin::press.index');
    }

    public function search(){

        $date = request()->all();
        $key = htmlspecialchars($date['keyword']);
        $page = htmlspecialchars($date['page']);
        $page = isset($page) ? $page : 1 ;
        $keyword = "%{$key}%";


        $school_id=getSchoolId();
        if(is_object($school_id)) {
            $school_id=Classify::ADMIN_SCHOOL;
        }
        $classify = Classify::where('school_id',$school_id)->get();
        $c_arr = [];
        foreach ($classify as $key => $val){
            $c_arr[] = $val->id;
        }

        $articles = Press::orwhere('title','like',$keyword)
            ->whereIn('classify_id',$c_arr)
            ->paginate($perPage = 10, $columns = ['*'], $pageName = 'page', $page);

        return response()->json($articles);
    }

}