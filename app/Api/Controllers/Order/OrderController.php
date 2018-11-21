<?php

namespace app\Api\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Charge;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Api\Resources\OrderResource;
use Tanmo\Wechat\Facades\Payment;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Packages\alipay\Alipay;
class OrderController extends Controller
{
    public function store(Charge $charge){
        $class_id = request()->get('class_id');
        $school_id=request()->get('school_id');
        $order =(new Order())->submit(auth('api')->user(), $charge,$class_id,$school_id);
        return api()->item($order, OrderResource::class)->created();
    }

    public function adbpay(Order $order){
        $user = auth('api')->user();
        $payment = Payment::app();

        ///
        $response = $payment->order()->adbunify([
            'body' => config('app.name') . '-订单:' . $order->sn,
            'out_trade_no' => $order->sn,
            'total_fee' => $order->price * 100,
            'trade_type' => 'APP',
            'notify_url' => url()->route('wechatpay.paid_notify')
        ]);

        $data = [
            'appid' => $payment->config()->getAdbAppId(),
            'partnerid' =>$payment->config()->getMchId(),
            'prepayid' => $response['prepay_id'],
            'package' => 'Sign=WXPay',
            'noncestr' => $response['nonce_str'],
            'timestamp' => (string)time(),
        ];
        $data['sign']=gen_sign($data, $payment->config()->getKey());

        return response()->json(['data' => $data]);
    }
    public function finish(Request $request){
        $aop=new \AopClient();
        $aop->alipayrsaPublicKey =config('alipay.alipay_key');
        $flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");

       if($flag && $sn = $request->get('out_trade_no')){
           $order = Order::where('sn',$sn)->first();
           $order->state = Order::FINISH;
           $order->pay_at = Carbon::now();
           $order->save();
           $this->setExpireAt($order);
           return Order::SUCCESS;
       }
    }

    public function alipay(Order $order){
        $pay = new Alipay();
        return $pay->generateOrder($order->sn,$order->school->name,$order->charge->time,$order->price,'15d');
    }

    public function success()
    {
        return Payment::app()->paidNotify(function ($message, $fail) {
            Log::notice(json_encode($message));

            /**
             * @var $order Order
             */

            if ($message['return_code'] === 'SUCCESS') {
                if (array_get($message, 'result_code') === 'SUCCESS') {
                    $sn = $message['out_trade_no'];
                    $order = Order::where('sn', $sn)->first();
                    $order->state = Order::FINISH;
                    $order->pay_at = Carbon::now();
                    $order->save();
                    $this->setExpireAt($order);
                } else {
                    Log::error('用户支付失败，SN:' . $message['out_trade_no']);
                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }
            return true;
        });
    }

    /**
     * 更新过期时间
     *
     * @param Order $order
     */
    protected function setExpireAt(Order $order)
    {
        $user = $order->user;
        $user_collective = $user->collectives()->where('class_id',$order->class_id)->first();
        $expore_at = $user_collective->pivot->expire_at;
        $date = date('Y-m-d H:i:s');
        $date_num = $order->charge->time;
        if(strtotime($expore_at)<strtotime($date)) {
            $times = strtotime($date) + $date_num*86400;
            $user_collective->pivot->expire_at = date('Y-m-d H:i:s',$times);
            $user_collective->pivot->save();
            return;
        }
        else {
            $times = strtotime($expore_at) + $date_num*86400;
            $user_collective->pivot->expire_at = date('Y-m-d H:i:s',$times);
            $user_collective->pivot->save();
            return;
        }
    }
}