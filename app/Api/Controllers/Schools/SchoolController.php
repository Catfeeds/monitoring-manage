<?php

namespace App\Api\Controllers\Schools;


use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller{

    public function findStudents(Request $request){
        $school_id = $request->school_id;
        $grade_id = $request->grades_id;
        $collective_id = $request->collective_id;
        if (!$school_id){
            $schools = School::where('state',1)->select('id','name')->get();
            $grades = [];
            $collectives = [];
        }else{
//            $schools = School::where('state',1)->where('id',$school_id)->select('id','name')->get();
            if (!$grade_id){
                $grades = Grade::where('school_id',$school_id)->selece('id','name')->get();
                $collectives = [];
            }
        }


    }
}