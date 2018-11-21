<?php

namespace App\Api\Controllers\User;

use app\Api\Controllers\SendMessage;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Api\Resources\FamilyResource;
use App\Models\UserCode;

class UserController extends Controller{
    /**
     * @param Student $student
     * @return \Tanmo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function family(Student $student)
    {
        $this->authorize('show',$student);
        $parents = $student->parents;

        return api()->collection($parents,FamilyResource::class);
    }

    /**
     * @param Student $student
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function phoneInvite(Student $student,Request $request)
    {
        $this->authorize('show',$student);

        $phone = $request->get('phone');
        $role = $request->get('role');

        $user = User::where('phone',$phone)->first();
        if(!$user) {
            $data = [
                'name' => $student->name.$role,
                'phone' => $phone,
                'password' => '123456',
            ];
            foreach ($data as $key => $val){
                if (!$val) return response()->json(['status'=>0,'message'=>'信息不完整']);
            }

            $data['password'] = bcrypt($data['password']);

            $user = User::create($data);

        }
        else {
            $result = $user->students()->where('student_id',$student->id)->exists();
            if($result) {
                return response()->json(['status' => 0, 'message' => '该用户已是家庭组成员！']);
            }
        }
        $user->students()->attach($student->id, ['role' => $role,'contact' => 0]);
        return response()->json(['status' =>1,'message' => '邀请成功！']);
    }

    public function inviteLink(Student $student)
    {
        $this->authorize('show',$student);

        $user = Auth()->user();
        $userCode = $user->userCode()->whereDate('expire_at','>=',date('Y-m-d H:i:s',strtotime('+1 hours')))->first();
        if(count($userCode) == 0) {
            $count = UserCode::where('user_id', '=', $user->id)->count();
            $str = md5($student->id . '_' . $count . '_' . time() . rand(100, 999));
            $expire_at = date('Y-m-d H:i:s',strtotime("+1 day"));

            $userCode = new UserCode(['code' => $str,'expire_at' => $expire_at ]);
            $userCode = $user->userCode()->save($userCode);
        }

        return response()->json(['invite_link' => route('user.invite',$userCode->code)]);
    }
}