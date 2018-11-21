<?php

namespace App\Admin\Controllers\Classify;

use App\Http\Controllers\Controller;
use App\Models\Classify;
use Illuminate\Http\Request;
use Tanmo\Admin\Facades\Admin;
/**
 * @module 新闻分类管理
 *
 * Class HomeController
 * @package App\Admin\Controllers
 */
class ClassifyController extends Controller
{
    /**
     * @permission 分类列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $school_id=getSchoolId();
        if(is_object($school_id)) {
            $school_id=Classify::ADMIN_SCHOOL;
        }
        $classifies=(new Classify())->orderBy('created_at','desc')->where('school_id', $school_id)->paginate(10);
        return view('admin::classify.classify',compact('classifies'));
    }
    /**
     * @permission 分类创建-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        return view('admin::classify.classify-create');
    }

    /**
     * @permission 分类创建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(Request $request){

        $school_id=getSchoolId();
        if(is_object($school_id)) {
            $school_id=Classify::ADMIN_SCHOOL;
        }
        $name = $request->get('name');
        Classify::create(['name'=>$name,'school_id'=>$school_id]);
        return redirect()->route('admin::classify.index');
    }

    /**
     * @permission 分类编辑-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Classify $classify){
        if(Admin::user()->can('operation',$classify)) {
            return view('admin::classify.classify-edit', compact('classify'));
        }
        return redirect()->route('admin::classify.index');
    }

    /**
     * @permission 分类更新
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Request $request,Classify $classify){
        if(Admin::user()->can('operation',$classify)) {
            $name = $request->get('name');
            $classify->name = $name;
            $classify->save();
        }
        return redirect()->route('admin::classify.index');
    }

    /**
     * @permission 分类删除
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function destroy(Classify $classify){
        if(Admin::user()->can('operation',$classify)) {
            $classify->delete();
            return response()->json(['status' => 1, 'message' => '删除成功']);
        }
        return redirect()->route('admin::classify.index');
    }
}