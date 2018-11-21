<?php

namespace app\Admin\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Navigation;
use Illuminate\Http\Request;
use Tanmo\Admin\Facades\Admin;
use App\Models\School;

class NavigationController extends Controller
{
    /**
     * @var array
     */
    protected $types = [
          'url' => '链接',
//        'goods' => '商品ID',
    ];

    public function index()
    {
        $header = '导航列表';
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

        $navigations = Navigation::where('school_id',$school_id)->get();
        return view('admin::home.navigations', compact('navigations','header'));
    }

    public function create()
    {
        return view('admin::home.navigation-create', ['types' => $this->types]);
    }

    public function store(Request $request)
    {
        $navigation = new Navigation();
        if(Admin::user()->isAdmin()){
            $school_id = getShowSchoolId();
            $navigation->school_id = $school_id;
        }
        else {
            $navigation->school_id = Admin::user()->school_id;
        }
        $navigation->title = $request->title;
        $navigation->order = $request->order;
        $navigation->status = $request->status;
        $http_val = $request->http;
        $type = $request->type;


        if($type == 'url'){
            switch ($http_val){
                case 1:
                    $url_pre = 'http://';
                    break;
                case 2:
                    $url_pre = 'https://';
                    break;
                default:
                    $url_pre = '';
            }
        }else{
            $url_pre = '';
        }
        $navigation->redirect = [
            'target' => $url_pre.$request->target,
            'type' => $type
        ];

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('navigation', 'public');
            $navigation->icon = $path;
        }

        $navigation->save();

        return redirect()->route('admin::navigations.index');
    }

    public function edit(Navigation $navigation)
    {
        if(Admin::user()->can('operation',$navigation)) {
            $header = '编辑导航';

            $str = $navigation->target;
            $needle1 = "http://";
            $needle2 = "https://";
            $result = str_replace($needle1,'',$str);
            $result = str_replace($needle2,'',$result);
            $navigation->target_2 =$result;
            $types = $this->types;
            return view('admin::home.navigation-edit', compact('navigation', 'types','header'));
        }
        else {
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::navigations.index');
        }
    }

    public function update(Navigation $navigation, Request $request)
    {
        if(Admin::user()->can('operation',$navigation)) {
            $navigation->title = $request->title;
            $navigation->order = $request->order;
            $navigation->status = $request->status;
            $http_val = $request->http;
            $type = $request->type;
            $url_pre = '';

            if ($type == 'url') {
                switch ($http_val) {
                    case 1:
                        $url_pre = 'http://';
                        break;
                    case 2:
                        $url_pre = 'https://';
                        break;
                    default:
                        $url_pre = '';
                }
            }
            $navigation->redirect = [
                'target' => $url_pre . $request->target,
                'type' => $type,
            ];
            if ($request->hasFile('icon')) {
                $path = $request->file('icon')->store('navigation', 'public');
                $navigation->icon = $path;
            }

            $navigation->save();

            return redirect()->route('admin::navigations.index');
        }
        else {
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::navigations.index');
        }
    }

    public function destroy(Navigation $navigation)
    {
        if(Admin::user()->can('operation',$navigation)) {
            $navigation->delete();

            return response()->json(['status' => 1, 'message' => '成功']);
        }
        else {
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::navigations.index');
        }
    }
}