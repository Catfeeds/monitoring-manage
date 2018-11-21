<?php

namespace App\Admin\Controllers\Works;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Tanmo\Admin\Facades\Admin;
use App\Models\Course;
use App\Models\Grade;
use App\Models\School;
use App\Models\Collective;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\admin\Controllers\Common\MyReadFilter;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;

/**
 * @module 学生课程
 *
 * Class CourseController
 * @package App\Admin\Controllers\Works
 */
class CourseController extends Controller
{
    /**
     * @permission 课程列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    */

    public function index()
    {
        $header = '学生课程';

        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->equal('id');
            $searcher->equal('grade_id');
        });

        if (Admin::user()->isAdmin()) {
            $school_id = getShowSchoolId();
            if ($school_id) {
                $school = (new School())->where('id', '=', $school_id)->first();
                $header = $school->name . "--" . $header;
            } else {
                return view('admin::errors.no_school');
            }
        } else {
            $school_id = Admin::user()->school_id;
        }
        $grades = Grade::where('school_id', $school_id)->latest()->get();
        if(Admin::user()->role) {
            $data = getTeacherClassIds(Admin::user()->id);
            $collectives = (new Collective())->search($searcher)->where('school_id',$school_id)->whereIn('id',$data['ids'])->with('grade')->latest()->paginate(10);
        }
        else {
            $collectives = (new Collective())->search($searcher)->where('school_id', $school_id)->with('grade')->latest()->paginate(10);
        }

        return view('admin::works.courses-courses', compact('header', 'collectives','grades'));
    }

    /**
     * @permission 设置课程
     *
     * @param Collective $collective
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function setCourse(Collective $collective,Request $request)
    {
        $header = '设置课程';
        if(Admin::user()->can('operation',$collective)) {
            //上下周
//            if ($request->get('date')) {
//                $course = Course::where([['begin_start', '=', $request->get('date')], ['class_id', '=', $collective->id]])->first();
//                if ($course) {
//                    return response()->json(['status' => 1, 'content' => $course->content]);
//                } else {
//                    return response()->json(['status' => 0]);
//                }
//            } else {
//                $week = get_week(strtotime(date('Y-m-d')));
//                $course = Course::where([['begin_start', '=', $week[0]['date']], ['class_id', '=', $collective->id]])->first();
//                if (!$course) {
//                    $course = new Course();
//                    $course->class_id = $collective->id;
//                    $course->begin_start = $week[0]['date'];
//
//                    $arr = ['C', 'D', 'E', 'F', 'G', 'H', 'I'];
//                    $content = [];
//                    foreach ($arr as $v) {
//                        $content['up'][$v] = '';
//                        $content['down'][$v] = '';
//                    }
//                    $course->content = $content;
//                    $course->save();
//                }
//                return view('admin::works.courses-create', compact('collective', 'week', 'header', 'course'));
//            }
            $week = get_week(strtotime(date('Y-m-d')));
            $course = Course::where('class_id', '=', $collective->id)->first();
            return view('admin::works.courses-create', compact('collective', 'week', 'header', 'course'));
        }
        else {
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::courses.index');
        }
    }

    /**
     * @permission 保存课程
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit(Request $request)
    {
        $class_id = $request->get('class_id');
        $begin_start = $request->get('begin_start');
        $course = Course::where('class_id','=',$class_id)->first();

        $arr = ['C','D','E','F','G','H','I'];
        $content = [];
        foreach ($arr as $v) {
            $content['up'][$v] = $request->get('up_'.$v) ?? '';
            $content['down'][$v] = $request->get('down_'.$v) ?? '';
        }

        if($course) {
            $course->content = $content;
            $course->save();
        }
        else {
            $course = new Course();
            $course->class_id = $class_id;
            $course->begin_start = $begin_start;
            $course->content = $content;
            $course->save();
        }
        return response()->json(['status' => 1,'message' => '课程保存成功！','content' => $course->content]);
    }

//    /**
//     * @permission 上一周课程
//     *
//     * @param Request $request
//     * @return \Illuminate\Http\JsonResponse
//     */
//    public function getPrevWeek(Request $request)
//    {
//        $time = strtotime($request->get('date')) - 3600 * 24 * 7;
//        $date = date('Y-m-d', $time);
//        $class_id = $request->get('class_id');
//
//        $course = Course::where([['class_id', '=', $class_id], ['begin_start', '=', $date]])->first();
//        return response()->json($course);
//    }

    /**
     * @permission 导出模板
     *
     * @param Collective $collective
     * @param Request $request
     */
    public function exportTemplate(Collective $collective,Request $request)
    {
        $date = $request->get('date');
        $week = get_week(strtotime($date));

        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();

        //表头
       // $worksheet->setTitle('视频在线表' . '(' . date('Y年m月d日') . ')');

        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
        $spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(true);

        //设置单元格内容
        $worksheet->setCellValueByColumnAndRow(1, 1, '班级');
        $worksheet->setCellValueByColumnAndRow(2, 1, '日期');
        $worksheet->setCellValueByColumnAndRow(3, 1, "星期一"."\n".$week[0]['date']);
        $worksheet->setCellValueByColumnAndRow(4, 1, '星期二'."\n".$week[1]['date']);
        $worksheet->setCellValueByColumnAndRow(5, 1, '星期三'."\n".$week[2]['date']);
        $worksheet->setCellValueByColumnAndRow(6, 1, '星期四'."\n".$week[3]['date']);
        $worksheet->setCellValueByColumnAndRow(7, 1, '星期五'."\n".$week[4]['date']);
        $worksheet->setCellValueByColumnAndRow(8, 1, '星期六'."\n".$week[5]['date']);
        $worksheet->setCellValueByColumnAndRow(9, 1, '星期日'."\n".$week[6]['date']);

        //设置列宽
        $worksheet->getColumnDimension('A')->setWidth(20);
        $worksheet->getColumnDimension('B')->setWidth(20);
        $worksheet->getColumnDimension('C')->setWidth(20);
        $worksheet->getColumnDimension('D')->setWidth(20);
        $worksheet->getColumnDimension('E')->setWidth(20);
        $worksheet->getColumnDimension('F')->setWidth(20);
        $worksheet->getColumnDimension('G')->setWidth(20);
        $worksheet->getColumnDimension('H')->setWidth(20);
        $worksheet->getColumnDimension('I')->setWidth(20);

        $worksheet->getRowDimension('1')->setRowHeight(80);
        $worksheet->getRowDimension('2')->setRowHeight(80);
        $worksheet->getRowDimension('3')->setRowHeight(80);

        $worksheet->setCellValueByColumnAndRow(1, 2,$collective->grade->name.'-'.$collective->name);
        $worksheet->setCellValueByColumnAndRow(2, 2,'上午');
        $worksheet->setCellValueByColumnAndRow(2, 3,'下午');


        //合并单元格
        $worksheet->mergeCells('A2:A3');

//        $styleArray = [
//            'font' => [
//                'bold' => true
//            ],
//            'alignment' => [
//                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
//            ],
//        ];
        //设置单元格样式
       // $worksheet->getStyle('A1:I1')->applyFromArray($styleArray)->getFont()->setSize(28);

        //$worksheet->getStyle('A2:H2')->applyFromArray($styleArray)->getFont()->setSize(16);

        $styleArrayBody = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];
        $total_rows =  3;
        //添加所有边框/居中
        $worksheet->getStyle('A1:I'.$total_rows)->applyFromArray($styleArrayBody);


        $filename = $collective->grade->name.'-'.$collective->name.'('.date('Y年m月d日').')'.'.xls';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');exit;
    }

    /**
     * @permission 导出课程
     *
     * @param Collective $collective
     * @param Request $request
     */
    public function export(Collective $collective,Request $request)
    {
        $date = $request->get('date');
        $week = get_week(strtotime($date));

        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();

        //表头
        // $worksheet->setTitle('视频在线表' . '(' . date('Y年m月d日') . ')');

        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
        $spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(true);

        //设置单元格内容
        $worksheet->setCellValueByColumnAndRow(1, 1, '班级');
        $worksheet->setCellValueByColumnAndRow(2, 1, '日期');
        $worksheet->setCellValueByColumnAndRow(3, 1, "星期一"."\n".$week[0]['date']);
        $worksheet->setCellValueByColumnAndRow(4, 1, '星期二'."\n".$week[1]['date']);
        $worksheet->setCellValueByColumnAndRow(5, 1, '星期三'."\n".$week[2]['date']);
        $worksheet->setCellValueByColumnAndRow(6, 1, '星期四'."\n".$week[3]['date']);
        $worksheet->setCellValueByColumnAndRow(7, 1, '星期五'."\n".$week[4]['date']);
        $worksheet->setCellValueByColumnAndRow(8, 1, '星期六'."\n".$week[5]['date']);
        $worksheet->setCellValueByColumnAndRow(9, 1, '星期日'."\n".$week[6]['date']);

        //设置列宽
        $worksheet->getColumnDimension('A')->setWidth(20);
        $worksheet->getColumnDimension('B')->setWidth(20);
        $worksheet->getColumnDimension('C')->setWidth(20);
        $worksheet->getColumnDimension('D')->setWidth(20);
        $worksheet->getColumnDimension('E')->setWidth(20);
        $worksheet->getColumnDimension('F')->setWidth(20);
        $worksheet->getColumnDimension('G')->setWidth(20);
        $worksheet->getColumnDimension('H')->setWidth(20);
        $worksheet->getColumnDimension('I')->setWidth(20);

        $worksheet->getRowDimension('1')->setRowHeight(80);
        $worksheet->getRowDimension('2')->setRowHeight(80);
        $worksheet->getRowDimension('3')->setRowHeight(80);

        $worksheet->setCellValueByColumnAndRow(1, 2,$collective->grade->name.'-'.$collective->name);
        $worksheet->setCellValueByColumnAndRow(2, 2,'上午');
        $worksheet->setCellValueByColumnAndRow(2, 3,'下午');


        //合并单元格
        $worksheet->mergeCells('A2:A3');

//        $styleArray = [
//            'font' => [
//                'bold' => true
//            ],
//            'alignment' => [
//                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
//            ],
//        ];
        //设置单元格样式
        // $worksheet->getStyle('A1:I1')->applyFromArray($styleArray)->getFont()->setSize(28);

        //$worksheet->getStyle('A2:H2')->applyFromArray($styleArray)->getFont()->setSize(16);

        $course = Course::where([['class_id','=',$collective->id],['begin_start','=',$date]])->first();

        if($course) {
            $k = 0;
            foreach($course->content as $v) {
                $i = 3;
                foreach($v as $v1) {
                    $worksheet->setCellValueByColumnAndRow($i++, $k+2, $v1);
                }
                $k++;
            }
        }

        $styleArrayBody = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];
        $total_rows =  3;
        //添加所有边框/居中
        $worksheet->getStyle('A1:I'.$total_rows)->applyFromArray($styleArrayBody);


        $filename = $collective->grade->name.'-'.$collective->name.'('.date('Y年m月d日').')'.'.xls';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');exit;
    }

    /**
     * @permission 导入课程
     *
     * @param Request $request
     * @return string
     */
    public function import(Request $request,Collective $collective)
    {
        if ($request->file('courseData')) {
            $str = date('YmdHis') . Admin::user()->id . rand(100, 999);
            $suffix = strtolower($request->file('courseData')->getClientOriginalExtension());
            if ($suffix != 'xls' && $suffix != 'xlsx') {
                return json_encode(['status' => 0, 'message' => '上传文件类型错误！']);
            }
            $filename = $str . '.' . $suffix;
            $path = $request->file('courseData')->storeAs('courseExcel', $filename, 'public');
            $columns = range('A','I');
            $class_name = $collective->grade->name.'-'.$collective->name;
            $result = $this->getImportInfo($path,$columns,$class_name,$suffix);
            return json_encode($result);
        }
        return json_encode(['status' => 0, 'message' => '上传失败！']);
    }

    protected function getImportInfo($path,$columns,$class_name,$suffix)
    {
        $path = Storage::url($path);
        if($suffix == 'xlsx') {
            $reader = new Xlsx();
        }
        else $reader = new Xls();
        $filterSubset = new MyReadFilter(1, 3, $columns);
        $reader->setReadFilter($filterSubset);
        $spreadsheet = $reader->load(public_path($path));

        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        unlink(public_path($path));
        $data = array_slice($sheetData,0,3);

        if(trim($data[1]['A']) != $class_name) {
            return ['status' => 0, 'message' => '导入失败！你当前选择的班级是['.$class_name.']'.'而文件中的班级是['.trim($data[1]['A']).']'];
        }

        $rules = "/\d{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30)))/";
        if(!preg_match($rules,$data[0]['C'],$matches)){
            return ['status' => 0, 'message' => '导入文件周一日期必需设置！'];
        }
        $date = $matches[0];

        $num = date('w', strtotime($date));
        if($num != 1) {
            return ['status' => 0, 'message' => '开始日期不是周一！'];
        }

        $content = [];
        foreach($data as $k => $v) {
            if ($k == 0) {
                continue;
            }
            foreach($v as $k1 => $v1){
                if ($k1 == 'A' || $k1 == 'B') {
                    continue;
                } else {
                    if($k == 1) {
                        $content['up'][$k1] = trim($v1);
                    }
                    else{
                        $content['down'][$k1] = trim($v1);
                    }
                }
            }
        }
        return ['status' => 1, 'message' => '导入成功！','week' => $date,'content'=>$content];
    }
}
