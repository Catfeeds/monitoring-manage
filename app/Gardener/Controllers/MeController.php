<?php

namespace app\Gardener\Controllers;

use App\Api\Resources\CollectiveResource;
use App\Api\Resources\QrcodeResource;
use App\Api\Resources\StudentinfoResource;
use App\Api\Resources\StudentlistResource;
use App\Api\Resources\StudentsResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\AlbumResource;
use App\Http\Resources\FileResource;
use App\Models\Album;
use App\Models\Collective;
use App\Models\Space;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Help;
use Illuminate\Http\Request;
use App\Api\Resources\CollectivesResource;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\Api\Resources\SchoolsResource;
class MeController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->user= auth('teacher')->user();
    }

    public function school(){
        $schools = $this->user->school;
        $schools->load('covers');
        return api()->item($schools,SchoolsResource::class);
    }

    public function studentinfo(Student $student){
        return api()->item($student,StudentinfoResource::class);
    }

    public function studentedit(Student $student,Request $request){
        $data =[
            'school_id'=>$this->user->school->id,
            'name'=>$request->get('name'),
            'birthday'=>$request->get('birthday'),
            'sex'=>$request->get('sex'),
            'class_id'=>$request->get('class_id'),
            'grade_id'=>$request->get('grade_id')
        ];
        foreach ($data as $key){
            if(!$key){
                return response()->json(['status'=>'0','msg'=>'参数不完整']);
            }
        }
        $student->update($data);
        return response()->json(['status'=>'1','msg'=>'修改成功']);
    }

    public function studentdestory(Student $student){
        $student->delete();
        return response()->json(['status'=>'1','msg'=>'操作成功']);
    }

    public function info(){
        $data = [
            'name'=>$this->user->name,
            'tel'=>$this->user->tel,
            'avatar'=>$this->user->avatar,
            'note'=>$this->user->note,
            'sex'=>$this->user->sex,
            'birthday'=>date('Y-m-d',strtotime($this->user->birthday)),
            'graduate' =>$this->user->graduate,
            'education'=>$this->user->education,
            'teach_age'=>$this->user->teach_age,
            'position'=>$this->user->position,
            'elegant'=>$this->user->elegant,
            'honor'=>$this->user->honor
        ];
        return response()->json( $data);
    }
    public function upinfo(Request $request){
        if($request->hasFile('avatar')){
            $path = $request->file('avatar')->store('teacher', 'public');
            $data = [
                'name'=>$request->get('name')?$request->get('name'):$this->user->name,
                'avatar'=>$path,
                'note'=>$request->get('note')?$request->get('note'):$this->user->note,
                'sex'=>$request->get('sex')?$request->get('sex'):$this->user->sex,
                'birthday'=>$request->get('birthday')?$request->get('birthday'):$this->user->birthday,
                'graduate' =>$request->get('graduate')?$request->get('graduate'):$this->user->graduate,
                'education'=>$request->get('education')?$request->get('education'):$this->user->education,
                'teach_age'=>$request->get('teach_age')?$request->get('teach_age'):$this->user->teach_age,
                'position'=>$request->get('position')?$request->get('position'):$this->user->position,
                'elegant'=>$request->get('elegant')?$request->get('elegant'):$this->user->elegant,
                'honor'=>$request->get('honor')?$request->get('honor'):$this->user->honor,
            ];
        }
        else {
            $data = [
                'name'=>$request->get('name')?$request->get('name'):$this->user->name,
                'note'=>$request->get('note')?$request->get('note'):$this->user->note,
                'sex'=>$request->get('sex')?$request->get('sex'):$this->user->sex,
                'birthday'=>$request->get('birthday')?$request->get('birthday'):$this->user->birthday,
                'graduate' =>$request->get('graduate')?$request->get('graduate'):$this->user->graduate,
                'education'=>$request->get('education')?$request->get('education'):$this->user->education,
                'teach_age'=>$request->get('teach_age')?$request->get('teach_age'):$this->user->teach_age,
                'position'=>$request->get('position')?$request->get('position'):$this->user->position,
                'elegant'=>$request->get('elegant')?$request->get('elegant'):$this->user->elegant,
                'honor'=>$request->get('honor')?$request->get('honor'):$this->user->honor,
            ];
        }

        $this->user->update($data);


        return response()->json(['status'=>1,'msg'=>'修改成功！']);
    }

    public function help(){
        $helps = Help::where([['status', '=', 1],['scope',Help::TEACHER]])->orderBy('created_at', 'desc')->get(['title', 'content']);

        return response()->json($helps);
    }


    public function collectives(){
        return api()->collection($this->user->collective,CollectivesResource::class);
    }

    public function addclass(Request $request){
        $sn = $request->get('sn');
        $collective = Collective::where('sn',$sn)->first();
        return api()->item($collective,CollectiveResource::class);
    }

    public function submitclass($class_id){
        $count = $this->user->collective()->where('class_id',$class_id)->count();
        if(!$count)
          $this->user->collective()->attach($class_id);
        else
            return response()->json(['status'=>0,'msg'=>'您已经添加过此班级']);

        return response()->json(['status'=>1,'msg'=>'添加成功']);
    }

    public function students(Collective $collective){
        $students = $collective->students;
        return api()->collection($students,StudentlistResource::class);
    }

    public function rePwd(Request $request)
    {
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

        $user = auth('teacher')->user();
        if(!Hash::check($request->old_pwd,$user->password)) {
            return response()->json(['status' => 0,'message'=>'旧密码不正确！']);
        }

        $user->password = bcrypt($request->get('new_pwd'));
        $user->save();

        return response()->json(['status' => 1,'message'=>'密码修改成功！']);
    }

    public function albumstore(Request $request){
        $title = $request->get('title');
        $class_id = $request->get('class_id');
        $album = Album::create(['class_id'=>$class_id,'title'=>$title]);
        $sn = date('Y-m-d');
        foreach ($request->file('imgs') as $img){
            $path = $img->store('space', 'public');
            $album->covers()->create(['sn'=>$sn,'path'=>$path]);
        }
        return response()->json(['status'=>1,'msg'=>'添加成功']);
    }

    public function albumdestroy(Album $album){
        $album->delete();
        return response()->json(['status'=>1,'msg'=>'删除成功']);
    }

    public function imgsadd(Request $request,Album $album){
        $imgs = $request->file('imgs');
        if($imgs == null){
            return response()->json(['status'=>1,'msg'=>'添加失败']);
        }
        $sn = date('Y-m-d');
        foreach ($imgs as $img){
            $path = $img->store('space', 'public');
            $album->covers()->create(['sn'=>$sn,'path'=>$path]);
        }
        return response()->json(['status'=>1,'msg'=>'添加成功']);
    }

    public function albumlist($class_id){
        $album = Album::where('class_id',$class_id)->get();
        return api()->collection($album,AlbumResource::class);

    }

    public function albumdetail(Album $album){
        $covers =$album->covers;
        $data =[];
        foreach ($covers as $key => $cover){
           $data[$cover->sn]['path'][] =$cover->path;
           $data[$cover->sn]['sn'] =$cover->sn;
        }
        $data = array_values($data);
        return response()->json($data);
    }

    public function spacefile($class_id){
        $files = Space::where('class_id',$class_id)->get();
        return api()->collection($files,FileResource::class);
    }

    public function qrcode(Collective $collective){
        return api()->item($collective,QrcodeResource::class);
    }
}