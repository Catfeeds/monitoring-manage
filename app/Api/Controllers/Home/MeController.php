<?php

namespace app\Api\Controllers\Home;

use App\Api\Resources\CollectiveResource;
use App\Api\Resources\OrderResource;
use App\Api\Resources\QrcodeResource;
use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Charge;
use App\Models\Collective;
use App\Models\School;
use Illuminate\Http\Request;
use App\Api\Resources\SchoolsResource;
use App\Api\Resources\StudentsResource;
use App\Api\Resources\StudentDetailResource;
use App\Api\Resources\TeacherResource;
use App\Api\Resources\ExpireResource;
use App\Models\Help;
use App\Models\Student;
use Validator;
use Illuminate\Support\Facades\Hash;

use App\Api\Resources\MaillistResource;
class MeController extends Controller
{

    public function helps()
    {
        $helps = (new Help())->where([['status', '=', 1],['scope',Help::USER]])->orderBy('created_at', 'desc')->get(['title', 'content']);
        return response()->json($helps);
    }

    public function about(){
        $about = (new About())->get(['content']);
        return response()->json($about);
    }

    public function charges(){
        $charges =(new Charge())->orderBy('created_at','desc')->get(['id','money','time']);
        return response()->json($charges);
    }

    public function password(Request $request){
        $rules = [
            'old_pwd' => 'required|min:6|max:20',
            'new_pwd' => 'required|min:6|max:20',
        ];

        //定义提示信息
        $messages = [
            'old_pwd.required' => '旧密码不能为空！',
            'new_pwd.required' => '新密码不能为空！',
            'old_pwd.min' => '密码不能少于6位！',
            'old_pwd.max' => '密码不能少于20位！',
            'new_pwd.min' => '旧密码不能多于20位！',
            'new_pwd.max' => '新密码不能多于20位！',
        ];

        $validator = Validator::make($request->all(),$rules,$messages);
        if(count($validator->errors())>0) {
            return response()->json(['status' => 0,'message' => $validator->errors()]);
        }

        $user = auth('api')->user();
        if(!Hash::check($request->old_pwd,$user->password)) {
            return response()->json(['status' => 0,'message'=>'旧密码不正确！']);
        }

        $user->password = bcrypt($request->get('new_pwd'));
        $user->save();

        return response()->json(['status' => 1,'message'=>'密码修改成功！']);
    }

//    public function phonepassword(Request $request){
//        $user = auth('api')->user();
//        if($user&&$request->get('newpassword')){
//            $user->password = bcrypt($request->get('newpassword'));
//            $user->save();
//            return response()->json(['state' =>'1','message'=>'密码修改成功']);
//        }
//        return response()->json(['state' =>'0','message'=>'密码修改失败']);
//    }


    public function schools(School $school)
    {
        $school->load('covers');
        return api()->item($school,SchoolsResource::class);
    }


    public function students()
    {
        $user = auth('api')->user();
        $students = $user->students;

        return api()->collection($students,StudentsResource::class);
    }

    public function maillist(Student $student){
       // dd($student);
     return api()->item($student,MaillistResource::class);
    }

//    public function addfriend(Request $request){
//        $phone = $request->get('phone');
//        $note = $request->get('note');
//        if($phone){
//            $friend = (new User())->where('phone',$phone)->first();
//            if($friend && $note){
//                auth('api')->user()->maillists()->attach($friend->id, ['note' => $note]);
//                return response()->json(['status'=>1,'msg'=>'添加成功']);
//            }
//            else{
//                return response()->json(['status'=>0,'msg'=>'添加的账号不存在']);
//            }
//        }
//        return response()->json(['status'=>0,'msg'=>'添加失败']);
//    }
    /**
     * @param Student $student
     * @return \Tanmo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function showStudent(Student $student)
    {
        $this->authorize('show',$student);

        $user = auth('api')->user();
        $student = $user->students()->where('student_id',$student->id)->first();

        return api()->item($student,StudentDetailResource::class);
    }

    /**
     * @param Student $student
     * @return \Tanmo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function teachers(Student $student)
    {
        $this->authorize('show',$student);

        return api()->collection($student->collective->teachers,TeacherResource::class);
    }

    public function orders(){
        return api()->collection(auth('api')->user()->orders->where('pay_at','!=',''),OrderResource::class);
    }



}