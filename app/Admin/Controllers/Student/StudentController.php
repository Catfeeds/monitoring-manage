<?php

namespace App\Admin\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;
use Tanmo\Admin\Facades\Admin;
use App\Models\School;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;
use App\Models\Collective;
use App\Models\Parents;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Models\Teacher;
/**
 * @module 学生管理
 *
 * Class HomeController
 * @package App\Admin\Controllers
 */
class StudentController extends Controller
{
    /**
     * @permission 学生列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('name');
        });

        $header = '学生列表';
        if(Admin::user()->isAdmin()){
            $school_id = getShowSchoolId();
            if($school_id ) {
                $school = (new School())->where('id', '=', $school_id)->first();
                $header = $school->name . "--" . $header;
            }
            else{
                return view('admin::errors.no_school');
            }
        }else {
            $school_id = Admin::user()->school_id;
        }
        if(Admin::user()->role) {
            $teacher = Teacher::where('admin_id',Admin::user()->id)->first();
            $classe = $teacher->collective;
            $ids=array();
            foreach ($classe as $k=>$v){
                $ids[$k]=$v->id;
            }
            $students = (new Student())->search($searcher)->orderBy('created_at', 'desc')->where('school_id', '=', $school_id)->whereIn('class_id',$ids)->paginate(10);
        }
        else{
            $students = (new Student())->search($searcher)->orderBy('created_at', 'desc')->where('school_id', '=', $school_id)->paginate(10);
        }
        session()->put('students',$students);
        return view('admin::students.student',compact('header','students'));
    }

    /**
     * @permission 学生创建-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){



        if(Admin::user()->isAdmin()){

            $school_id = getShowSchoolId();

        }else {
            $school_id = Admin::user()->school_id;
        }

        $grades = (new Grade())->where('school_id','=',$school_id)->get();
        return view('admin::students.student-create',compact('grades'));
    }


    /**
     * @permission 学生创建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(Request $request){

        $student = new Student();
        $student->name= $request->get('name');
        $student->birthday = $request->get('birthday');
        $student->sex = $request->get('sex');
        $student->grade_id = $request->get('grade_id');
        $guarder =$request->get('guarder');
        $guardername = $request->get('guardername');
        $guardertel = $request->get('guardertel');
        $student->class_id = $request->get('class_id');


        if(Admin::user()->isAdmin()){
            $school_id = getShowSchoolId();
            $student->school_id = $school_id;
        }
        else {
            $student->school_id = Admin::user()->school_id;
        }
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('student', 'public');
            $student->avatar = $path;
        }
        $student->save();

        $parents = array();
        $error = array();
        $flag =0;
        if($guarder) {
            for ($i = 0; $i < count($guarder); $i++) {
                if ((new User())->where('phone', '=', $guardertel[$i])->count() > 0) {
                    $px = (new User())->where('phone', '=', $guardertel[$i])->where('name','=',$guardername[$i])->first();
                    if($px){
                        $parents[ $px->id] = ['role' => $guarder[$i]];
                        $px->collectives()->attach($student->class_id);
                        $px->schools()->attach( $student->school_id);
                    }
                    array_push($error,$guarder[$i]);
                    $flag = 1;
                    continue;
                }
                $parent = new User();
                $parent->name = $guardername[$i];
                $parent->phone = $guardertel[$i];
                $parent->avatar = '/student/default.png';
              //  $parent->role = $guarder[$i];
                $parent->password = bcrypt('123456');
                $parent->save();
                $parent->schools()->attach($school_id);
                $parent->collectives()->attach($student->class_id);
                $parents[$parent->id] = ['role' => $guarder[$i]];
            }
        }
        $student->parents()->sync($parents);
        if($flag){
            $errors = '';
            foreach ( $error as $err)
                $errors .=$err.' ';

            session()->flash('error',collect(['title'=>['表单错误'],'message'=>[$errors.'手机号码已存在']]));
            return redirect()->route('admin::students.index');
        }
        return redirect()->route('admin::students.index');


    }

    /**
     * @permission 学生编辑-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Student $student){


        if(Admin::user()->can('operation',$student)) {
            if (Admin::user()->isAdmin()) {

                $school_id = getShowSchoolId();

            } else {
                $school_id = Admin::user()->school_id;
            }

            $grades = (new Grade())->where('school_id', '=', $school_id)->get();
            $class = (new Collective())->where('id', '=', $student->class_id)->first();
            $classes = $class->grade->collectives;

            $parents = $student->parents;
            return view('admin::students.student-edit', compact('student', 'grades', 'classes','parents'));
        }
        return redirect()->route('admin::students.index');
    }

    /**
     * @permission 学生编辑
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Student $student,Request $request){
        if(Admin::user()->can('operation',$student)) {

            $student->name = $request->get('name');
            $student->birthday = $request->get('birthday');
            $student->sex = $request->get('sex');
            $student->grade_id = $request->get('grade_id');
            $guarder = $request->get('guarder');
            $guardername = $request->get('guardername');
            $guardertel = $request->get('guardertel');

            $guarders = $request->get('guarders');
            $guardernames = $request->get('guardernames');
            $guardertels = $request->get('guardertels');
            $ids =  $request->get('ids');

            $student->class_id = $request->get('class_id');
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('student', 'public');
                $student->avatar = $path;
            }
            
            $student->save();
            $parents = array();
            $error = array();
            $flag=0;
           if($guarders) {
                for($i=0;$i<count($guarders);$i++){

                    $parent =(new User())->where('id','=',$ids[$i])->first();
                    $parent->name = $guardernames[$i];

                    //$parent->role = $guarders[$i];
                    $parent->save();
                    $parents[$parent->id] = ['role' => $guarders[$i]];

                }
            }
             if($guarder) {

                for($i=0;$i<count($guarder);$i++){
                    if ((new User())->where('phone', '=', $guardertel[$i])->count() > 0) {
                        $px = (new User())->where('phone', '=', $guardertel[$i])->where('name','=',$guardername[$i])->first();
                       if($px){
                           $parents[ $px->id] = ['role' => $guarder[$i]];
                           $px->collectives()->attach($student->class_id);
                           $px->schools()->attach( $student->school_id);
                       }
                       else {
                           array_push($error, $guarder[$i]);
                           $flag = 1;
                       }
                        continue;
                    }
                    $parent = new User();
                    $parent->name = $guardername[$i];
                    $parent->phone = $guardertel[$i];
                    //  $parent->role = $guarder[$i];
                    $parent->avatar = Storage::disk('public')->url('/student/default.png');
                    $parent->password = bcrypt('123456');
                    $parent->save();
                    $parent->schools()->attach($student->school_id);
                    $parent->collectives()->attach($student->class_id);
                    $parents[$parent->id] = ['role' => $guarder[$i]];
                }
            }
//            dd($parents);
//            $t = [20=>['role'=>'lll'],21=>['role'=>'sfafasf']];
//            mlog('text',$t);
//            mlog('text',$parents);

            $student->parents()->sync($parents);
//            $student->parents()->sync($parents);
            if($flag){
                $errors = '';
                foreach ( $error as $err)
                    $errors .=$err.' ';

                session()->flash('error',collect(['title'=>['表单错误'],'message'=>[$errors.'手机号码已存在']]));
                return redirect()->route('admin::students.index');
            }
            return redirect()->route('admin::students.index');
        }
        return redirect()->route('admin::students.index');
    }

    /**
     * @permission 学生删除
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function destroy(Student $student){
        if(Admin::user()->can('operation',$student)) {
            $student->delete();
            return response()->json(['status' => 1, 'message' => '设置成功']);
        }
        return redirect()->route('admin::students.index');
    }

    /**
     * @permission 学生毕业/离校
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(){
        $header = '离校/毕业学生列表';

        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('name');
        });

        if(Admin::user()->isAdmin()){
            $school_id = getShowSchoolId();
            if($school_id ) {
                $school = (new School())->where('id', '=', $school_id)->first();
                $header = $school->name . "--" . $header;
            }
            else{
                return view('admin::errors.no_school');
            }
        }else {
            $school_id = Admin::user()->school_id;
        }
        if(Admin::user()->role) {
            $teacher = Teacher::where('admin_id',Admin::user()->id)->first();
            $classe = $teacher->collective;
            $ids=array();
            foreach ($classe as $k=>$v){
                $ids[$k]=$v->id;
            }
            $students = (new Student())->search($searcher)->orderBy('created_at', 'desc')->where('school_id', '=', $school_id)->whereIn('class_id',$ids)->onlyTrashed()->paginate(10);
        }
        else{
            $students = (new Student())->search($searcher)->orderBy('created_at', 'desc')->where('school_id', '=', $school_id)->onlyTrashed()->paginate(10);
        }
        return view('admin::students.awaystudent',compact('students','header'));
    }

    public function reduction($student){

        if(Admin::user()->isAdmin()){
            $school_id = getShowSchoolId();

        }else {
            $school_id = Admin::user()->school_id;
        }
        $student = Student::withTrashed()->where('school_id','=',$school_id)->find($student);
        $student->restore();

        return redirect()->route('admin::students.index');
    }


    /**
     * @permission 导出学生列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function export()
    {

        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();

        //表头
        $worksheet->setTitle('学生信息表' . '(' . date('Y年m月d日') . ')');

        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);

        //设置单元格内容
        $worksheet->setCellValueByColumnAndRow(1, 1, '学生信息表');
        $worksheet->setCellValueByColumnAndRow(1, 2, '学生姓名');
        $worksheet->setCellValueByColumnAndRow(2, 2, '性别');
        $worksheet->setCellValueByColumnAndRow(3, 2, '年级');
        $worksheet->setCellValueByColumnAndRow(4, 2, '班级');
        $worksheet->setCellValueByColumnAndRow(5, 2, '学生添加时间');


        //设置列宽
        $worksheet->getColumnDimension('A')->setWidth(30);
        $worksheet->getColumnDimension('B')->setWidth(30);
        $worksheet->getColumnDimension('C')->setWidth(30);
        $worksheet->getColumnDimension('D')->setWidth(30);
        $worksheet->getColumnDimension('E')->setWidth(30);


        //合并单元格
        $worksheet->mergeCells('A1:E1');

        $styleArray = [
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        //设置单元格样式
        $worksheet->getStyle('A1')->applyFromArray($styleArray)->getFont()->setSize(28);

        $worksheet->getStyle('A2:H2')->applyFromArray($styleArray)->getFont()->setSize(16);

        $students = session('students');
        $k = 0;
        foreach($students as $student) {
                $worksheet->setCellValueByColumnAndRow(1, $k+3, $student->name);
                $worksheet->setCellValueByColumnAndRow(2, $k+3, $student->sex);
                $worksheet->setCellValueByColumnAndRow(3, $k+3, $student->grade->name);
                $worksheet->setCellValueByColumnAndRow(4, $k+3, $student->collective->name);
                $worksheet->setCellValueByColumnAndRow(5, $k+3, $student->created_at);

                $k++;
        }

        $styleArrayBody = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '666666'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $total_rows = count($students) + 3;
        //添加所有边框/居中
        $worksheet->getStyle('A1:H'.$total_rows)->applyFromArray($styleArrayBody);

        $filename = '学生信息列表'.'('.date('Y年m月d日').')'.'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');exit;
    }


    public function del(User $user,Student $student){
        $user->students()->detach($student->id);
        $user->collectives()->detach($student->class_id);
        $user->schools()->detach($student->school_id);
        return response()->json(['status' => 1, 'message' => '删除成功']);
    }

}