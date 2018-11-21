<?php

namespace App\Admin\Controllers\Space;

use App\Http\Controllers\Controller;
use App\Models\Camera;
use App\Models\Space;
use Illuminate\Http\Request;
use Tanmo\Admin\Facades\Admin;
use App\Models\School;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;
use App\Models\Grade;
use App\Models\Collective;
use App\Models\Teacher;
/**
 * @module 班级文件管理
 *
 * Class HomeController
 * @package App\Admin\Controllers
 */
class SpaceController extends Controller
{
    /**
     * @permission 班级文件列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('class_id');
        });

        $header = '班级文件列表';
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

        $grades = (new Grade())->where('school_id','=',$school_id)->get();
        if(request('class_id')) {
            if(Admin::user()->role) {
                $teacher = Teacher::where('admin_id', Admin::user()->id)->first();
                $classe = $teacher->collective;
                $ids = array();
                foreach ($classe as $k => $v) {
                    $ids[$k] = $v->id;
                }
                $spaces = (new Space())->search($searcher)->whereIn('class_id', $ids)->orderBy('created_at','desc')->paginate(10);
            }
            else{
                $spaces = (new Space())->search($searcher)->orderBy('created_at','desc')->paginate(10);
            }
            $collective = (new Collective())->where('id',request('class_id'))->first();
            $header=$header.'--'.$collective->grade->name.$collective->name;
            $class_id=request('class_id');
            return view('admin::space.space',compact('grades','spaces','header'));
        }
        $header=$header.'--请选择自己的班级';
        return view('admin::space.space',compact('grades','header'));
    }
    public function destroy(Space $space){
        if(Admin::user()->can('operation',$space)) {
            $space->delete();
            return response()->json(['status' => 1, 'message' => '删除成功']);
        }
    }
    public function create(){

        if(Admin::user()->isAdmin()){

            $school_id = getShowSchoolId();

        }else {
            $school_id = Admin::user()->school_id;
        }

        $grades = (new Grade())->where('school_id','=',$school_id)->get();
        return view('admin::space.space-create',compact('grades'));
    }
    public function store(Request $request){
        if(($request->file('image')||$request->file('file'))&&$request->get('class_id')){
            $space = new Space();
            $collective = (new Collective())->where('id',request('class_id'))->first();
            if($request->file('file')) {
                $space->file = $request->file('file')->storeAs('file', $request->file('file')->getClientOriginalName(), 'public');
                $tail =$request->file('file')->getClientOriginalExtension();

                if( $tail =='doc' || $tail =='docx'){
                    $space->type = Space::DOC_TYPE;
                }
                elseif($tail =='xls' || $tail =='xlsx'){
                    $space->type = Space::XLS_TYPE;
                }
            }
            $space->class_id=$request->get('class_id');
            $space->admin_id=Admin::user()->id;
            $space->save();
             return redirect()->route('admin::spaces.index',['class_id'=>$collective->id]);
        }
    }


}