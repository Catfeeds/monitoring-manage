<?php

namespace app\Admin\Controllers\Campus;

use App\Http\Controllers\Controller;
use App\Models\Collective;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Tanmo\Admin\Facades\Admin;
use App\Models\School;
use App\Models\Grade;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;

/**
 * @module 班级
 *
 * Class CollectiveController
 * @package app\Admin\Controllers\Campus
 */
class CollectiveController extends Controller
{

    public function __construct(){
        $this->middleware('admin.check_permission',['except' => ['makeQrCode']]);
    }


    /**
     * @permission 班级列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->equal('grade_id');
            $searcher->like('name');
        });

        $header = '班级列表';
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
        $collectives = (new Collective())->search($searcher)->with('students','parents')->where('school_id',$school_id)->latest()->paginate(10);
        $grades = Grade::where('school_id',$school_id)->get();
        return view('admin::campus.collectives-collectives',compact('collectives','grades','header'));
    }

    /**
     * @permission 新增班级
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $collective = new Collective();
        $collective->grade_id= $request->get('grade_id');
        $collective->name = $request->get('name');
        $collective->remark = $request->get('remark');

        if(Admin::user()->isAdmin()) {
            $school_id = getShowSchoolId();
        } else {
            $school_id = Admin::user()->school_id;
        }

        $collective->school_id = $school_id;

        $collective->save();

        $this->makeQrCode($collective);
        return redirect()->route('admin::collectives.index');
    }

    /**
     * @permission 编辑班级
     *
     * @param Request $request
     * @param Collective $collective
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,Collective $collective)
    {
        if(Admin::user()->can('operation',$collective)) {
            $collective->grade_id= $request->get('grade_id');
            $collective->name = $request->get('name');
            $collective->remark = $request->get('remark');
            $collective->save();
            $this->makeQrCode($collective);
            return response()->json(['status' => 1, 'message' => '修改成功']);
        }
        else {
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::collectives.index');
        }
    }

    public function checkName()
    {
        $name = request()->get('name');
        $current_name = request()->get('current_name');
        $grade_id = request()->get('grade_id');

        if($current_name || $current_name == '0') {
            if( $current_name == $name) {
                return '{"valid":true}';
            }
        }

        $collective = Collective::where([['grade_id','=',$grade_id],['name','=',$name]])->first();
        if($collective) {
            return '{"valid":false}';
        }
        return '{"valid":true}';
    }

    /**
     *  @permission 删除班级
     *
     * @param Collective $collective
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Collective $collective)
    {
        if(Admin::user()->can('operation',$collective)) {
            if($collective->students->count()>0 || $collective->teachers->count()>0) {
                return response()->json(['status' => 0, 'message' => '删除失败!该班级下存在学生和老师']);
            }
            $collective->delete();
            return response()->json(['status' => 1, 'message' => '成功']);
        }
        else {
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::collectives.index');
        }
    }

    /**
     * @permission 联动班级
     *
     * @param $grade
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($grade){
        $classes =(new Grade())->where('id','=',$grade)->first()->collectives;
        return response()->json($classes);
    }

    /**
     * 生成唯一标识
     * @param $collective
     * @return bool
     */
    public function makeQrCode($collective){


        $grade = $collective->grade;
        $school = $collective->school;
//        $school_master = Admin::where('school_id',$school->id)->where('role',null)->first();

        $qrCode_name = time().str_pad($collective->id,5,"0",STR_PAD_LEFT);
        $save_path = '/storage/qrcode/'.$qrCode_name.'.png';
        $sn =str_pad($collective->school_id,'3','0',STR_PAD_RIGHT).str_pad($collective->grade_id,'3','0',STR_PAD_LEFT).str_pad($collective->id,'3','0',STR_PAD_LEFT);

        if (!Storage::exists('public/qrcode/')){
            Storage::makeDirectory('public/qrcode/');
        }
        $data = [
            '<code>'=>$sn
        ];
        $data = json_encode($data);
        QrCode::format('png')->size(200)->encoding('UTF-8')->generate($data,public_path($save_path));
        $collective->qrcode = $save_path;
        $collective->sn = $sn;

        $collective->save();

        return true;
    }

}