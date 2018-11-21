<?php

namespace app\Admin\Controllers\Campus;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\School;
use Tanmo\Admin\Facades\Admin;

class AboutController extends Controller
{
	public function index()
    {
        $header = '关于我们';
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
        $about = About::where('school_id',$school_id)->first();
        return view('admin::campus.abouts-edit',compact('about','header'));
    }


    public function update(Request $request,About $about)
    {
        if(Admin::user()->can('operation',$about)) {
            $about->content = $request->get('content');
            $about->save();

            return redirect()->route('admin::abouts.index');
        }
        else {
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::abouts.index');
        }
    }
}