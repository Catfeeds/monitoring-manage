<?php

namespace App\admin\Controllers\Works;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PayConfig;
use Tanmo\Admin\Facades\Admin;
use App\Models\School;

/**
 * @module 园所缴费
 *
 * Class PayConfigController
 * @package App\admin\Controllers\Works
 */
class PayConfigController extends Controller
{
    /**
     * @permission 支付设置
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $header = '支付设置';
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
        $payConfig = PayConfig::where('school_id',$school_id)->first();
        return view('admin::works.payConfig-edit',compact('payConfig','header'));
    }

    /**
     * @permission 编辑支付设置
     *
     * @param Request $request
     * @param PayConfig $payConfig
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,PayConfig $payConfig)
    {
        if(Admin::user()->can('operation',$payConfig)) {
            if($request->file('alipay_code')) {
                $path = $request->file('alipay_code')->store('pay_codes', 'public');
                $payConfig->alipay_code = $path;
            }
            if($request->file('wechat_code')) {
                $path = $request->file('wechat_code')->store('pay_codes', 'public');
                $payConfig->wechat_code = $path;
            }
            $payConfig->bank_name = $request->get('bank_name');
            $payConfig->bank_card = $request->get('bank_card');
            $payConfig->bank_man = $request->get('bank_man');
            $payConfig->bank_place = $request->get('bank_place');
            $payConfig->title = $request->get('title');
            $payConfig->content = $request->get('content');
            $payConfig->release_man = Admin::user()->name;

            $payConfig->save();

            return redirect()->route('admin::payConfigs.index');
        }
        else {
            session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return redirect()->route('admin::payConfigs.index');
        }
    }
}
