<?php

namespace App\Admin\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Label;
use App\Models\Student;
use Illuminate\Http\Request;
use Tanmo\Admin\Facades\Admin;
use App\Models\School;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;
use App\Models\Collective;
use App\Models\Parents;
use App\Models\User;
/**
 * @module 标签管理
 *
 * Class HomeController
 * @package App\Admin\Controllers
 */
class LabelController extends Controller
{
    /**
     * @permission 标签列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $labels = Label::orderBy('sort','desc')->orderBy('created_at','desc')->paginate(10);
        return view('admin::article.labels',compact('labels'));
    }

    public function create(){
        return view('admin::article.label-create');
    }

    public function store(Request $request){
        Label::create(['name'=>$request->get('name'),'sort'=>$request->get('sort')]);
        return redirect()->route('admin::labels.index');
    }

    public function edit(Label $label){
        return view('admin::article.label-edit',compact('label'));
    }
    public function update(Request $request, Label $label){
        $label->update(['name'=>$request->get('name'),'sort'=>$request->get('sort')]);
        return redirect()->route('admin::labels.index');
    }
    public function destroy(Label $label){
        $label->delete();
        return response()->json(['status' => 1, 'message' => '删除成功']);
    }
}







