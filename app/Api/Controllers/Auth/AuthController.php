<?php

namespace App\Api\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth;
use App\Models\Collective;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * 用户认证管理
 *
 * Class ItemController
 * @package App\Admin\Controllers\Items
 */
class AuthController extends Controller{

    public function addChild(Request $request){

        $user = auth('api')->user();
        foreach ( $request->all() as $key => $val){
            if (!$val) return response()->json(['status'=>0,'message'=>'信息不完整']);
        }

        $student = new Student();
        $student->name= $request->get('name');
        $student->birthday = $request->get('birthday');
        $student->sex = $request->get('sex');

        if($avatar = $request->file('avatar')) {
            $path = $avatar->store('avatar', 'public');
            $student->avatar = $path;
        }



        $student->save();

        $role = $request->get('relation');

        $user->students()->attach($student->id, ['role' => $role,'contact' => 1]);
//        $student->parents()->attach($user->id, ['role' => $role]);

        return response()->json(['status'=>1,'message'=>'添加成功']);
    }


    /**
     * 用户认证申请列表
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apply(Request $request)
    {
        $user = auth('api')->user();

        $student_id = $request->student_id;

        $data = [
            'class_id' => $request->class_id,
            'status' => Auth::APPLYING,
            'student_id' => $student_id,
        ];
        foreach ($data as $k => $v){
            if (!$v) return response()->json(['status'=>0,'message'=>'信息不完整']);
        }
        $collective = Collective::find($data['class_id']);
        if (!$collective) return response()->json(['status'=>0,'message'=>'查无此班级']);

        $auth_log = Auth::where('student_id',$data['student_id'])->where('class_id',$data['class_id'])->first();
        if ($auth_log) return response()->json(['status'=>0,'message'=>'申请记录已存在']);

        $data['remark'] = htmlspecialchars($request->remark);
        $relation = $user->students->where('id',$student_id)->first()->pivot->role;
        $input_relation = [
            'user_id' => $user->id,
            'parent_phone' => $user->phone,
            'parent_name' => $user->name,
            'relation' => $relation,
        ];

        $info = [
            $input_relation,
        ];

        $data['info'] = json_encode($info);
        Auth::create($data);
        return response()->json(['status'=>1,'message'=>'申请提交成功']);

    }


    /**
     * 验证学号
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkSchoolNum(Request $request){
        $schoolNum = $request->schoolNum;
        $student = Student::where('school_num',$schoolNum)->first();
        if (!$student){
            return response()->json(['status'=>0,'message'=>'查无此学号']);
        }
        return response()->json(['status'=>1,'message'=>'学号无误']);
    }



  
}