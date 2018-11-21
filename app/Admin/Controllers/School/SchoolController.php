<?php

namespace App\Admin\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Tanmo\Admin\Models\Administrator;
use Tanmo\Admin\Facades\Admin;
use Tanmo\Admin\Requests\AdministratorRequest;
use App\Models\Charge;
/**
 * @module 学校管理
 *
 * Class HomeController
 * @package App\Admin\Controllers
 */
class SchoolController extends Controller
{
    /**
     * @permission 学校列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $header = '学校列表';
        $schools =(new School())->orderBy('created_at', 'desc')->paginate(10);
        return view('admin::schools.school',compact('schools','header'));

    }

    /**
     * @permission 学校创建-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        return view('admin::schools.school-create');
    }


    /**
     * @permission 学校创建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(AdministratorRequest $request){

        if((new Administrator())->where('username','=', $request->get('username'))->count()>0){
            session()->flash('error',collect(['title'=>['表单错误'],'message'=>['当前账号已存在']]));
            return redirect()->route('admin::schools.create');
        }

        $school = new School();
        $school->name = $request->get('sname');
        if ($request->hasFile('avatar_school')) {
            $path = $request->file('avatar_school')->store('school', 'public');
            $school->avatar = $path;
        }
        $school->phone = $request->get('phone');
        $school->address = $request->get('address');
        $school->describe = $request->get('describe');
        $school->detail_info = $request->get('detail_info');
        $school->save();
        if ($request->hasFile('sence_school')) {
            foreach ($request->file('sence_school') as $file) {
                $path = $file->store('school', 'public');
                $school->covers()->create(['path'=>$path]);
            }
        }
        setSchools();


        $administrator = new Administrator();

        $administrator->username =  $request->get('username');
        $administrator->password =  bcrypt($request->get('password'));
        $administrator->name =  $request->get('name');
        $administrator->avatar = $this->getAvatar($request);
        $administrator->school_id = $school->id;

        $administrator->save();

        if ($administrator) {
            $roleIds = array_filter(['2']);
            $administrator->roles()->sync($roleIds);
        }
        return redirect()->route('admin::schools.index');
    }

    /**
     * @permission 学校编辑-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(School $school){
        return view('admin::schools.school-edit',compact('school'));
    }

    /**
     * @permission 学校编辑
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(School $school,Request $request){
        $school->name = $request->get('name');
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('school', 'public');
            $school->avatar = $path;
        }
        if ($request->hasFile('sence_school')) {
            foreach ($request->file('sence_school') as $file) {
                $path = $file->store('school', 'public');
                $school->covers()->create(['path'=>$path]);
            }
        }
        $school->phone = $request->get('phone');
        $school->address = $request->get('address');
        $school->describe = $request->get('describe');
        $school->detail_info = $request->get('detail_info');
        $school->save();
        setSchools();
        return redirect()->route('admin::schools.index');
    }

    /**
     * @permission 学校删除
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function destroy(School $school){
        $administrators = (new Administrator())->where('school_id','=',$school->id)->get();
        foreach ($administrators as $administrator){
            $administrator->state = $administrator->state?0:1;
            $administrator->save();
        }
        $school->state =  $school->state?0:1;
        $school->save();
        return response()->json(['status' => 1, 'message' => '更改成功']);
    }

    public function show(School $school){
        if(!Admin::user()->isAdmin())
            return;

        $ip = getIp();
        Redis::set($ip."show_school_id",$school->id);
        $school_name ="当前学校为".$school->name;
        session()->flash('success',collect(['title'=>['学校选择成功'],'message'=>[$school_name ]]));
        if(!IS_AJAX) {
            return redirect()->route('admin::schools.index');
        }

    }

    protected function getAvatar(Request $request)
    {
        if ($request->hasFile('avatar')) {
            return $avatar = $request->file('avatar')->store('avatar', config('admin.upload.disk.avatar'));
        }
        return null;
    }
}