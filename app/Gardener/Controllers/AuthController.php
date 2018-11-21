<?php

namespace app\Gardener\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collective;
use App\Models\TeacherApply;
use App\Models\Teacher;
use Auth;
use Validator;
use App\Api\Controllers\SendMessage;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    /**
     *登录
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request){
        $params = array(
            'tel'=>$request->get('phone'),
            'password'=>$request->get('password')
        );
        return ($token = Auth::guard('teacher')->attempt($params))
            ? response([
                'state'=> 1,
                'access_token' => $token,
                'token_type' => 'bearer',
            ])
            : response(['state'=>0,'error' => '账号或密码错误']);
    }

    public function checkClassSn(Request $request)
    {
        //定义验证规则，是一个数组
        $rules = [
            'sn' => 'required|max:9|exists:collectives,sn',
        ];

        //定义提示信息
        $messages = [
            'sn.required' => '班级编号不能为空！',
            'sn.max' => '班级编号长度过长！',
            'sn.exists' => '输入的班级编号不存在！',
        ];

        $validator = Validator::make($request->all(),$rules,$messages);
        if(count($validator->errors())>0) {
            return response()->json(['status' => 0,'message' => $validator->errors()]);
        }

        else {
            return response()->json(['status' => 1,'message' => '验证成功！']);
        }

    }

    public function register(Request $request)
    {
        //定义验证规则，是一个数组
        $rules = [
            'name' => 'required|max:20',
            'avatar' => 'required|image',
            'sex' => 'required',
            'phone' => [
                'required',
                'unique:teachers,tel',
                'regex:/^(13[0-9]|14[579]|15[0-3,5-9]|16[6]|17[0135678]|18[0-9]|19[89])\d{8}$/'
            ],
            'password' => 'required|min:6|max:20',
            'code' => 'required'
        ];

        //定义提示信息
        $messages = [
            'name.required' => '用户名称不能为空！',
            'name.max' => '用户名称长度过长！',
            'avatar.required' => '头像不能为空！',
            'avatar.image' => '必须上传图片！',
            'sex.required' => '性别不能为空！',
            'phone.required' => '手机号不能为空！',
            'phone.unique' => '该账号已注册！',
            'phone.regex' => '手机号格式错误！',
            'password.required' => '密码不能为空！',
            'password.min' => '密码不能少于6位！',
            'password.max' => '密码不能多于20位！',
            'code.require' => '验证码不能为空！',
        ];

        $validator = Validator::make($request->all(),$rules,$messages);
        if(count($validator->errors())>0) {
            return response()->json(['status' => 0,'message' => $validator->errors()]);
        }
        $teacherApply =TeacherApply::where([['status',0],['tel',$request->get('phone')]])->first();
        if($teacherApply) {
            return response()->json(['status' => 0,'message' => '该账号注册已提交审核,请等待管理员审核！']);
        }

        $sn = $request->get('sn');
        $collective = Collective::where('sn',$sn)->first();
        if(!$collective) {
            return response()->json(['status' => 0,'message' => '班级信息不存在！']);
        }
        $school_id = $collective->school->id;

        $data['code'] = $request->get('code');
        $data['phone'] = $request->get('phone');
        $sendMessage = new SendMessage();
        if(!$sendMessage->checkGardenerRegister($data)) {
            return response()->json(['status'=> 0,'message'=>'验证码错误或已过期']);
        }

        $teacherApply = new TeacherApply();
        $teacherApply->name = $request->get('name');
        $teacherApply->tel = $request->get('phone');
        $teacherApply->password = bcrypt($request->get('password'));
        $teacherApply->sex = $request->get('sex');
        $teacherApply->school_id = $school_id;
        $teacherApply->class_id = $collective->id;
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('teacher', 'public');
            $teacherApply->avatar = $path;
        }
        $teacherApply->save();

//        $administrator = new Administrator();
//
//        $administrator->username =  $request->get('phone');
//        $administrator->password =  bcrypt($request->get('password'));
//        $administrator->name =  $request->get('name');
//        $administrator->avatar = $this->getAvatar($request);
//        $administrator->role = $collective->id;
//        $administrator->school_id = $school_id;
//
//        $administrator->save();
//
//        $teacher = new Teacher();
//        $teacher->name = $request->get('name');
//        $teacher->tel = $request->get('phone');
//        $teacher->sex = $request->get('sex');
//        $teacher->state = 0;
//        $teacher->password = bcrypt($request->get('password'));
//        $teacher->school_id = $school_id;
//        $teacher->admin_id =  $administrator->id;
//        if ($request->hasFile('avatar')) {
//            $path = $request->file('avatar')->store('teacher', 'public');
//            $teacher->avatar = $path;
//        }
//        $teacher->save();
//
//        $teacher->collective()->attach($collective->id);
        $sendMessage = new SendMessage();
        $rsg = $sendMessage->getRegSuccess($request->get('phone'));
        if($rsg) {
            return response()->json(['status' => 1,'message' => '注册申请成功！通知短信已成功发送！']);
        }

        return response()->json(['status' => 1,'message' => '注册申请成功！通知短信发送失败！']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function reGardenerPwd(Request $request)
    {
        $phone = $request->get('phone');
        $code = $request->get('code');
        $pwd = $request->get('password');

        $sendMessage = new SendMessage();
        $data['phone'] = $phone;
        $data['code'] = $code;
        $rsg = $sendMessage->checkGardenerCode($data);
        if(!$rsg) {
            return response(['status' => 0,'message' => '校验失败！请重新操作！' ]);
        }
        $teacher = Teacher::where('tel',$phone)->first();
        if(!$teacher) {
            return response(['status' => 0,'message' => '校验失败！请重新操作！' ]);
        }
        $teacher->password = bcrypt($pwd);
        $teacher->save();
        Cache::forget($phone.$rsg);
        return response(['status' => 1,'message' => '重置密码成功！' ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkGardenerCode(Request $request)
    {
        $sendMessage = new SendMessage();
        $code = $request->get('code');
        $phone = $request->get('phone');
        $data['phone'] = $phone;
        $data['code'] = $code;
        $rsg = $sendMessage->checkGardenerCode($data);

        if($rsg) {
            return response()->json(['status'=> 1,'message'=>'验证成功']);
        }
        return response()->json(['status'=> 0,'message'=>'验证码错误或已过期']);
    }
}