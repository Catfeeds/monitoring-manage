<?php

namespace App\Admin\Controllers\GardenPay;

use App\Http\Controllers\Controller;
use App\Models\Camera;
use App\Models\Gardenpay;
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
 * @module 园所缴费管理
 *
 * Class HomeController
 * @package App\Admin\Controllers
 */
class GardenPayController extends Controller
{
    /**
     * @permission 园所缴费
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('class_id');
        });

        $header = '园所缴费';
        $cannt='no';
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
                if(in_array(request('class_id'),$ids)){
                    $collective = (new Collective())->where('id',request('class_id'))->first();
                    $header=$header.'--'.$collective->grade->name.$collective->name;
                    $gardenpay = $collective->gardenpay;
                    return view('admin::gardenpay.gardenpay',compact('grades','header','gardenpay'));
                }
                return view('admin::gardenpay.gardenpay',compact('grades','header','cannt'));
            }
            else{
                $collective = (new Collective())->where('id',request('class_id'))->first();
                $header=$header.'--'.$collective->grade->name.$collective->name;
                $gardenpay = $collective->gardenpay;
                return view('admin::gardenpay.gardenpay',compact('grades','header','gardenpay'));
            }
        }
        $header=$header.'--请选择自己的班级';
        return view('admin::gardenpay.gardenpay',compact('grades','header','cannt'));
    }

    public function store(Request $request){
        $account=$request->get('account');
        $name = $request->get('name');
        $class_id  =$request->get('class_id');
        $collective = (new Collective())->where('id',request('class_id'))->first();
        if(isset($collective->gardenpay)){
            $gardenpay = $collective->gardenpay;
            $gardenpay->account = $account;
            $gardenpay->name= $name;
        }
        else{
            $gardenpay = new Gardenpay();
            $gardenpay->account = $account;
            $gardenpay->name= $name;
            $gardenpay->class_id=$class_id;
        }
        if($request->file('qrcode')){
            $path  = $request->file('qrcode')->store('qrcode','public');
            $gardenpay->qrcode = $path;
        }
        $gardenpay->save();
        return redirect()->route('admin::gardenpays.index',['class_id'=>$class_id]);
    }
}