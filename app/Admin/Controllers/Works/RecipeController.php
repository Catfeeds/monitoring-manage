<?php

namespace App\admin\Controllers\Works;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tanmo\Admin\Facades\Admin;
use App\Models\Recipe;
use App\Models\School;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use App\admin\Controllers\Common\MyReadFilter;
use Illuminate\Support\Facades\Storage;

/**
 * @module 学生食谱
 *
 * Class RecipeController
 * @package App\admin\Controllers\Works\
 */
class RecipeController extends Controller
{
    protected $dates = [
        'monday' => '星期一',
        'tuesday' => '星期二',
        'wednesday' => '星期三',
        'thursday' => '星期四',
        'friday' => '星期五',
        'saturday' => '星期六',
        'sunday' => '星期日'
    ];

    protected $tags = ['one', 'two', 'three', 'four', 'five', 'six', 'seven'];

    /**
     * @permission 食谱列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $header = '学生食谱';

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

//        $start = request()->get('start');
//        $end = request()->get('end');

//        $recipes = Recipe::select(['id', 'begin_start'])->where('school_id', $school_id)->TimeInterval($start,$end)->latest()->paginate(10);
        $recipe = Recipe::where('school_id', $school_id)->first();
        $dates = $this->dates;
        $tags = $this->tags;

//        return view('admin::works.recipes-recipes', compact('header', 'recipes'));
        return view('admin::works.recipes-edit', compact('header', 'recipe','tags', 'dates'));
    }

//    /**
//     * @permission 创建食谱-页面
//     *
//     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
//     */
//    public function create()
//    {
//        $header = '创建食谱';
//        $dates = $this->dates;
//        $tags = $this->tags;
//
//        return view('admin::works.recipes-create', compact('header', 'dates', 'tags'));
//    }

//    /**
//     * @permission 创建食谱
//     *
//     * @param Request $request
//     * @return \Illuminate\Http\RedirectResponse
//     */
//    public function store(Request $request)
//    {
//        if (Admin::user()->isAdmin()) {
//            $school_id = getShowSchoolId();
//        } else {
//            $school_id = Admin::user()->school_id;
//        }
//        $recipe = new Recipe();
//        $recipe->school_id = $school_id;
//        $recipe->begin_start = $request->begin_start;
//        $arr = [];
//        $tags = [];
//        foreach ($this->dates as $k => $date) {
//            foreach ($this->tags as $k1 => $v) {
//                if ($k == 'monday') {
//                    $tags[$v] = $request->$v;
//                }
//                $name = $k . '_' . $v;
//                $arr[$k][$v] = $request->$name;
//            }
//        }
//        $recipe->tags = $tags;
//        $recipe->content = $arr;
//        $recipe->save();
//        return redirect()->route('admin::recipes.index');
//    }

//    /**
//     * @permission 编辑食谱-页面
//     *
//     * @param Recipe $recipe
//     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
//     */
//    public function edit(Recipe $recipe)
//    {
//        if (Admin::user()->can('operation', $recipe)) {
//            $header = '编辑食谱';
//            $dates = $this->dates;
//            $tags = $this->tags;
//
//            return view('admin::works.recipes-edit', compact('header', 'recipe', 'dates', 'tags'));
//        } else {
//            session()->flash('error', collect(['title' => ['操作错误'], 'message' => ['当前用户权限不足']]));
//            return redirect()->route('admin::recipes.index');
//        }
//    }

    /**
     * @permission 编辑食谱
     *
     * @param Request $request
     * @param Recipe $recipe
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Recipe $recipe)
    {
        if (Admin::user()->can('operation', $recipe)) {
            $arr = [];
            foreach ($this->dates as $k => $date) {
                foreach ($this->tags as $k1 => $v) {
                    if ($k == 'monday') {
                        $tags[$v] = $request->$v;
                    }
                    $name = $k . '_' . $v;
                    $arr[$k][$v] = $request->$name;
                }
            }
            $recipe->tags = $tags;
            $recipe->content = $arr;
            $recipe->save();
            return response()->json(['status' => 1,'message' => '食谱保存成功！']);
        } else {
            session()->flash('error', collect(['title' => ['操作错误'], 'message' => ['当前用户权限不足']]));
            return redirect()->route('admin::recipes.index');
        }
    }

//    /**
//     * @permission 删除食谱
//     *
//     * @param Recipe $recipe
//     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
//     * @throws \Exception
//     */
//    public function destroy(Recipe $recipe)
//    {
//        if (Admin::user()->can('operation', $recipe)) {
//            $recipe->delete();
//            return response()->json(['status' => 1, 'message' => '成功']);
//        } else {
//            session()->flash('error', collect(['title' => ['操作错误'], 'message' => ['当前用户权限不足']]));
//            return redirect()->route('admin::recipes.index');
//        }
//    }

//    /**
//     * @permission 复制上周食谱
//     *
//     * @param Request $request
//     * @return \Illuminate\Http\JsonResponse
//     */
//    public function getPrevWeek(Request $request)
//    {
//        if (Admin::user()->isAdmin()) {
//            $school_id = getShowSchoolId();
//        } else {
//            $school_id = Admin::user()->school_id;
//        }
//        $time = strtotime($request->get('date')) - 3600 * 24 * 7;
//        $date = date('Y-m-d', $time);
//
//        $recipe = Recipe::where([['school_id', '=', $school_id], ['begin_start', '=', $date]])->first();
//        return response()->json($recipe);
//    }

    /**
     * @permission 导入食谱
     *
     * @param Request $request
     * @return string
     */
    public function import(Request $request)
    {
        if ($request->file('foodData')) {
            $str = date('YmdHis') . Admin::user()->id . rand(100, 999);
            $suffix = strtolower($request->file('foodData')->getClientOriginalExtension());
            if ($suffix != 'xls' && $suffix != 'xlsx') {
                return json_encode(['status' => 0, 'message' => '上传文件类型错误！']);
            }
            $filename = $str . '.' . $suffix;
            $path = $request->file('foodData')->storeAs('foodExcel', $filename, 'public');
            $columns = range('A','H');
            $result = $this->getImportInfo($path,$columns,$suffix);
            if($result) {
                return json_encode(['status' => 1, 'message' => '导入成功','tags' => $result[0],'content' => $result[1]]);
            }
            return json_encode(['status' => 0, 'message' => '上传文件内容格式不符，请下载模板文件进行导入！']);
        }
        return json_encode(['status' => 0, 'message' => '上传失败！']);
    }

    /**
     * @param $path
     * @param $columns
     * @return array|bool
     */
    protected function getImportInfo($path,$columns,$suffix)
    {
        $path = Storage::url($path);
        if($suffix == 'xlsx') {
            $reader = new Xlsx();
        }
        else $reader = new Xls();
        $filterSubset = new MyReadFilter(1, 8, $columns);
        $reader->setReadFilter($filterSubset);
        $spreadsheet = $reader->load(public_path($path));
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        unlink(public_path($path));
        $data = array_slice($sheetData,0,8);

        $week = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday','saturday','sunday'];
        $flag = true;
        for($i=0;$i<count($data)-1;$i++)
        {
            if(trim($data[$i+1]['A']) != $this->dates[$week[$i]]) {
               $flag = false;
               break;
            }
        }

        if($flag) {
            $info = [];
            foreach($data as $k => $v) {
                for($i=0;$i<count($v)-1;$i++){
                    if($k == 0) {
                        $info[0][$this->tags[$i]] = trim($v[$columns[$i+1]]);
                    }
                    else{
                        $info[1][$week[$k-1]][$this->tags[$i]] = trim($v[$columns[$i+1]]);
                    }
                }
            }
            return $info;
        }
        return $flag;
    }

//    /**
//     * @param Request $request
//     * @return \Illuminate\Http\JsonResponse
//     */
//    public function checkDate(Request $request)
//    {
//        if (Admin::user()->isAdmin()) {
//            $school_id = getShowSchoolId();
//        } else {
//            $school_id = Admin::user()->school_id;
//        }
//
//        $date = $request->get('date');
//
//        $recipe = Recipe::where([['school_id', '=', $school_id], ['begin_start', '=', $date]])->first();
//        if($recipe) {
//           return response()->json(['status' => 0,'message' => '当前周食谱已经存在！']);
//        }
//        else{
//           return response()->json(['status' => 1]);
//        }
//    }
}
