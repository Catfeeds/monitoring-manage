<?php

namespace App\Admin\Controllers\Charge;

use App\Http\Controllers\Controller;
use App\Models\Charge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Tanmo\Admin\Models\Administrator;
use Tanmo\Admin\Facades\Admin;
use App\Models\School;
use App\Models\Classify;
/**
 * @module 财务管理
 *
 * Class HomeController
 * @package App\Admin\Controllers
 */
class ChargeController extends Controller
{
    /**
     * @permission 收费设置
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $charges = (new Charge())->paginate(10);

        return view('admin::charges.charge',compact('charges'));
    }

    /**
     * @permission 删除收费标准
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function destroy(Charge $charge){
            $charge->delete();
            return response()->json(['status' => 1, 'message' => '删除成功']);
    }


    /**
     * @permission 修改收费设置
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Charge $charge,Request $request){

            $charge->money = $request->get('money');
            $charge->time = $request->get('time');
            $charge->save();
            return redirect()->route('admin::charges.index');
    }

    /**
     * @permission 编辑收费标准
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Charge $charge){

            return view('admin::charges.charge-edit', compact('charge'));

    }


    /**
     * @permission 创建收费标准-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        return view('admin::charges.charge-create');
    }

    public function store(Request $request){

        $charge = new Charge();
        $charge->money = $request->get('money');
        $charge->time = $request->get('time');
        $charge->save();
        return redirect()->route('admin::charges.index');
    }






}