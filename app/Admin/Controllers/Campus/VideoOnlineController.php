<?php

namespace app\Admin\Controllers\Campus;

use App\Http\Controllers\Controller;
use Tanmo\Admin\Facades\Admin;
use App\Models\School;
use App\Models\Student;
use App\Models\Grade;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;
use Illuminate\Http\Request;

/**
 * @module 视频在线
 *
 * Class VedioOnlineController
 * @package app\Admin\Controllers\Campus
 */
class VideoOnlineController extends Controller
{
    protected static $students;

    /**
     * @permission 视频在线
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('name');
            $searcher->equal('grade.id','grade_id');
            $searcher->like('collective.name','collective_name');
            $searcher->like('parents.name','parent_name');
            $searcher->like('parents.phone','phone');
        });

        $header = '视频在线';
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
        $students = (new Student())->search($searcher)->where('school_id',$school_id)->with(['grade','collective','parents.collectives'])->paginate(10);
        session()->put('students',$students);
        $grades = Grade::where('school_id',$school_id)->get();
        return view('admin::campus.video-online',compact('students','grades','header'));
    }

    /**
     * @permission 视频在线导出表
     *
     */
    public function export()
    {
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();

        //表头
        $worksheet->setTitle('视频在线表' . '(' . date('Y年m月d日') . ')');

        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);

        //设置单元格内容
        $worksheet->setCellValueByColumnAndRow(1, 1, '视频在线表');
        $worksheet->setCellValueByColumnAndRow(1, 2, '家长名称');
        $worksheet->setCellValueByColumnAndRow(2, 2, '联系方式');
        $worksheet->setCellValueByColumnAndRow(3, 2, '学生姓名');
        $worksheet->setCellValueByColumnAndRow(4, 2, '年级');
        $worksheet->setCellValueByColumnAndRow(5, 2, '班级');
        $worksheet->setCellValueByColumnAndRow(6, 2, '家长来源');
        $worksheet->setCellValueByColumnAndRow(7, 2, '视频在线状态');
        $worksheet->setCellValueByColumnAndRow(8, 2, '视频剩余天数');

        //设置列宽
        $worksheet->getColumnDimension('A')->setWidth(30);
        $worksheet->getColumnDimension('B')->setWidth(30);
        $worksheet->getColumnDimension('C')->setWidth(30);
        $worksheet->getColumnDimension('D')->setWidth(30);
        $worksheet->getColumnDimension('E')->setWidth(30);
        $worksheet->getColumnDimension('F')->setWidth(30);
        $worksheet->getColumnDimension('G')->setWidth(30);
        $worksheet->getColumnDimension('H')->setWidth(30);

        //合并单元格
        $worksheet->mergeCells('A1:H1');

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
            foreach($student->parents as $parent) {
                $worksheet->setCellValueByColumnAndRow(1, $k+3, $parent->name);
                $worksheet->setCellValueByColumnAndRow(2, $k+3, $parent->phone);
                $worksheet->setCellValueByColumnAndRow(3, $k+3, $student->name);
                $worksheet->setCellValueByColumnAndRow(4, $k+3, $student->grade->name);
                $worksheet->setCellValueByColumnAndRow(5, $k+3, $student->collective->name);
                $worksheet->setCellValueByColumnAndRow(6, $k+3, $parent->way);
                $worksheet->setCellValueByColumnAndRow(7, $k+3, strtotime($parent->collectives()->where('class_id',$student->collective->id)->first()->pivot->expire_at)>strtotime(date('Y-m-d H:i:s'))?'已开通':'未开通');
                $worksheet->setCellValueByColumnAndRow(8, $k+3, abs(ceil((strtotime($parent->collectives()->where('class_id',$student->collective->id)->first()->pivot->expire_at)-strtotime(date('Y-m-d H:i:s')))/86400)));
                $k++;
            }
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

        $filename = '视频在线列表'.'('.date('Y年m月d日').')'.'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');exit;
    }
}