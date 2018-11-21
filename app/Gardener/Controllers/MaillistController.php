<?php

namespace app\Gardener\Controllers;

use App\Api\Resources\ParentsResource;
use App\Api\Resources\StudentResource;
use App\Api\Resources\StudentsResource;
use App\Api\Resources\TeacherResource;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\User;
use Illuminate\Http\Request;
use Tanmo\Admin\Facades\Admin;
use App\Models\School;
use App\Http\Resources\BannersResource;
use App\Models\Collective;
class MaillistController extends Controller
{


    public function index($class_id){
        $collective = Collective::where('id',$class_id)->first();
        $teachers = $collective->teachers->where('id','!=',auth('teacher')->user()->id);
        $parents = $collective->parents;
        $dateset =[];
        foreach ($parents as $k=>$parent){
            $students = $parent->students->where('class_id',$class_id);
            $dateset[$k]['parent']=new ParentsResource($parent);
            $dateset[$k]['student']=StudentResource::collection($students);
        }
        $date =[
            'teachers'=>TeacherResource::collection($teachers),
            'parents'=>$dateset
        ];
        return response()->json($date);
    }
    public function words(){
        //todo
    }
}