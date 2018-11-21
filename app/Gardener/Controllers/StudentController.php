<?php

namespace App\Gardener\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;
use Tanmo\Admin\Facades\Admin;
use App\Models\School;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;
use App\Models\Collective;
use App\Models\Parents;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Models\Teacher;

class StudentController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->user = auth('teacher')->user();
    }

    public function store(Request $request){

       $dates =[
           'name'=>$request->get('name'),
           'sex'=>$request->get('sex'),
           'birthday'=> $request->get('birthday'),
           'grade_id'=>$request->get('grade_id'),
           'class_id'=>$request->get('class_id'),
           'school_id'=> $this->user->school->id,
       ];
       foreach ($dates as $date){
           if($date == null)
               return response()->json(['status'=>0,'msg'=>'参数错误']);
       }

       $student = Student::create($dates);
        $parents = $request->get('parents');
        $parents=json_decode($parents, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            return api()->error('Submission of data errors', 400);
        }
        $ids = [];
        foreach ($parents as $parent){
            if(!preg_match('/^1[3578]\d{9}$/',$parent['phone'])){
                return response()->json(['status'=>0,'msg'=>'手机格式不正确']);
            }
        }
        foreach ($parents as $parent){
            $user = User::where('phone',$parent['phone'])->first();
            if($user == null){
               $user =User::create(['phone'=>$parent['phone'],'name'=>'自定义','password'=>bcrypt('123456')]);
            }
            $ids[$user->id] =['role'=>$parent['role']];
        }
        $student->parents()->sync($ids);
        return response()->json(['status'=>1,'msg'=>'添加成功']);
   }

   public function edit(Student $student,Request $request){
       $dates =[
           'name'=>$request->get('name'),
           'sex'=>$request->get('sex'),
           'birthday'=> $request->get('birthday'),
           'grade_id'=>$request->get('grade_id'),
           'class_id'=>$request->get('class_id'),
           'school_id'=> $this->user->school->id,
       ];
       foreach ($dates as $date){
           if($date == null)
               return response()->json(['status'=>0,'msg'=>'参数错误']);
       }

       $parents = $request->get('parents');
       $parents=json_decode($parents, true);
       if (json_last_error() != JSON_ERROR_NONE) {
           return api()->error('Submission of data errors', 400);
       }
       $ids = [];

       foreach ($parents as $parent){
           if(!preg_match('/^1[3578]\d{9}$/',$parent['phone'])){
               return response()->json(['status'=>0,'msg'=>'手机格式不正确']);
           }
       }

       $student->update($dates);
       foreach ($parents as $parent){
           $user = User::where('phone',$parent['phone'])->first();
           if($user == null){
               $user =User::create(['phone'=>$parent['phone'],'name'=>'自定义','password'=>bcrypt('123456')]);
           }
           $ids[$user->id] =['role'=>$parent['role']];
       }
       $student->parents()->sync($ids);
       return response()->json(['status'=>1,'msg'=>'修改成功']);
   }

}