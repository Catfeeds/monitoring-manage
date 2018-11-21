<?php

namespace App\Api\Controllers\Collectives;


use App\Http\Controllers\Controller;
use App\Models\Collective;
use Illuminate\Http\Request;

class CollectiveController extends Controller{

    public function getFromSn(Request $request){
        $sn = $request->sn;
        $collective = Collective::where('sn',$sn)->first();

        if (!$collective) return response()->json(['status'=>1,'data'=>'查无学校']);
        $grade = $collective->grade;
        $school = $collective->school;

        $data = [
            'label' => 'quErWang',
            'school_id' => $school->id,
            'school_name' => $school->name,
            'school_cover' => $school->avatar,
            'school_tel' => $school->phone,
            'school_address' => $school->address,
            'grade_id' => $grade->id,
            'grade_name' => $grade->name,
            'collective_id' => $collective->id,
            'collective_name' => $collective->name,
        ];

        return response()->json(['status'=>1,'data'=>$data]);
    }
}