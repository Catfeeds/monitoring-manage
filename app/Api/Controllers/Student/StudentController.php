<?php
namespace App\Api\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * @param Request $request
     * @param Student $student
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request,Student $student)
    {
        $this->authorize('show',$student);
        if($request->file('avatar')) {
            $path = $request->file('avatar')->store('users', 'public');
            $student->avatar = $path;
        }

        if($request->get('name')) {
            $student->name = $request->get('name');
        }

        if($request->get('birthday')) {
            $student->birthday = $request->get('birthday');
        }

        if($request->get('sex') || $request->get('sex') == '0') {
            $student->sex = $request->get('sex');
        }

        $res = $student->save();

        if($res) {
            return response()->json(['status'=>1,'message' => '修改成功']);
        }
        else {
            return response()->json(['status'=>0,'message' => '修改失败']);
        }
    }


}