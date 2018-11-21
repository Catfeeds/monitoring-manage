<?php

namespace app\Api\Controllers;

ini_set("display_errors", "on");

require_once app_path('Packages/msg_sdk/vendor/autoload.php');

use App\Http\Controllers\Controller;
use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Illuminate\Support\Facades\Cache;
use Aliyun\Api\Sms\Request\V20170525\SendBatchSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;

Config::load();

class SendMessage extends Controller
{
    static $acsClient = null;

    protected $templates = [
        'change' => 'SMS_145151264',    //信息变更验证码
        'forget' => 'SMS_145151265',    //修改密码验证码
        'register' => 'SMS_145151266',  //用户注册验证码
        'abnormal' => 'SMS_145151267',  //登录异常验证码
        'login' => 'SMS_145151268',     //登录确认验证码
        'auth' => 'SMS_145151269',      //身份验证验证码
        'gardenerRegister' => 'SMS_148860472', //园丁端注册验证码
        'garRegSuccess' => 'SMS_148866222', //用户注册申请成功通知
        'applySuccess' => 'SMS_148860899', //审核成功通知
        'applyFail' => 'SMS_148866230', //审核失败通知
        're_pwd' => 'SMS_148860481' //园丁端重置密码验证码
    ];

    public static function getAcsClient()
    {
        $accessKeyId = config('alimsg.access_key_id');

        $accessKeySecret = config('alimsg.access_key_secret');


        // 短信API产品名
        $product = "Dysmsapi";


        // 短信API产品域名
        $domain = "dysmsapi.aliyuncs.com";


        // 暂时不支持多Region
        $region = "cn-hangzhou";


        // 服务结点
        $endPointName = "cn-hangzhou";


        if(static::$acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }

    /**
     * @param $phone
     * @param $code
     * @return mixed
     */
    public static function sendSms($phone,$code,$param) {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        //可选-启用https协议
        //$request->setProtocol("https");

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($phone);

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName(config('alimsg.sign_name'));

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode($code);

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $request->setTemplateParam($param);

        // 可选，设置流水号
        //$request->setOutId("yourOutId");

        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
        //$request->setSmsUpExtendCode("1234567");

        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);

        return $acsResponse;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        $phone = $request->get('phone');
        $user = User::where('phone',$phone)->first();
        if ($user) return response()->json(['status'=>'2','message'=>'该手机号已注册']);

        $code = $this->templates['register'];
        $code_key = randomKeys(6);
        $param = json_encode(array("code"=> $code_key),JSON_UNESCAPED_UNICODE);

        $res = static::sendSms($phone,$code,$param);

        if($res->Code == 'OK') {
            Cache::put($phone.$code,$code_key,5);
            return response()->json(['status'=>1,'message'=>'发送成功']);
        }

        return response()->json(['status'=>0,'message'=>'发送失败']);
    }

    /**
     * @param $data
     * @return bool
     */
    public function checkRegisterCode($data)
    {
        $code = $data['code'];
        $phone = $data['phone'];
        $template = $this->templates['register'];
        $code_key = Cache::get($phone.$template);
        if($code == $code_key) {
            Cache::forget($phone.$template);
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function confirm(Request $request)
    {
        $phone = $request->get('phone');
        $code = $this->templates['auth'];
        $code_key = randomKeys(6);
        $param = json_encode(array("code"=> $code_key),JSON_UNESCAPED_UNICODE);

        $res = static::sendSms($phone,$code,$param);

        if($res->Code == 'OK') {
            Cache::put($phone.$code,$code_key,5);
            return response()->json(['status'=>1,'message'=>'发送成功']);
        }

        return response()->json(['status'=>0,'message'=>'发送失败']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkConfirmCode(Request $request)
    {
        $code = $request->get('code');
        $phone = $request->get('phone');
        $template = $this->templates['auth'];
        $code_key = Cache::get($phone.$template);
        if($code == $code_key) {
            Cache::forget($phone.$template);
            return response()->json(['status'=> 1,'message'=>'验证成功']);
        }
        else {
            return response()->json(['status'=> 0,'message'=>'验证码错误或已过期']);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function forget(Request $request)
    {
        $phone = $request->get('phone');
        $user = User::where('phone',$phone)->first();

        if(!$user) {
            return response()->json(['status'=>0,'message'=>'该账号未注册']);
        }

        $code = $this->templates['forget'];
        $code_key = randomKeys(6);
        $param = json_encode(array("code"=> $code_key),JSON_UNESCAPED_UNICODE);

        $res = static::sendSms($phone,$code,$param);

        if($res->Code == 'OK') {
            Cache::put($phone.$code,$code_key,5);
            return response()->json(['status'=>1,'message'=>'发送成功']);
        }

        return response()->json(['status'=>0,'message'=>'发送失败']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkForgetCode(Request $request)
    {
        $code = $request->get('code');
        $phone = $request->get('phone');
        $template = $this->templates['forget'];
        $code_key = Cache::get($phone.$template);
        if($code == $code_key) {
//            Cache::forget($phone.$template);
            return response()->json(['status'=> 1,'message'=>'验证成功']);
        }
        else {
            return response()->json(['status'=> 0,'message'=>'验证码错误或已过期']);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function gardenerRegister(Request $request)
    {
        $phone = $request->get('phone');
        $teacher = Teacher::where('tel',$phone)->first();
        if ($teacher) return response()->json(['status'=>'2','message'=>'该手机号已注册']);

        $code = $this->templates['gardenerRegister'];
        $code_key = randomKeys(6);
        $param = json_encode(array("code"=> $code_key),JSON_UNESCAPED_UNICODE);

        $res = static::sendSms($phone,$code,$param);

        if($res->Code == 'OK') {
            Cache::put($phone.$code,$code_key,5);
            return response()->json(['status'=>1,'message'=>'发送成功']);
        }

        return response()->json(['status'=>0,'message'=>'发送失败']);
    }

    /**
     * @param $data
     * @return bool
     */
    public function checkGardenerRegister($data)
    {
        $code = $data['code'];
        $phone = $data['phone'];
        $template = $this->templates['gardenerRegister'];
        $code_key = Cache::get($phone.$template);
        if($code == $code_key) {
            Cache::forget($phone.$template);
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @param $phone
     * @return bool
     */
    public function getRegSuccess($phone)
    {
        $code = $this->templates['garRegSuccess'];
        $param = json_encode(array("phone"=> $phone),JSON_UNESCAPED_UNICODE);

        $res = static::sendSms($phone,$code,$param);

        if($res->Code == 'OK') {
            return true;
        }

        return false;
    }

    public function applySuccess($phone)
    {
        $code = $this->templates['applySuccess'];
        $param = json_encode(array("phone"=> $phone),JSON_UNESCAPED_UNICODE);

        $res = static::sendSms($phone,$code,$param);

        if($res->Code == 'OK') {
            return true;
        }

        return false;
    }

    public function applyFail($phone)
    {
        $code = $this->templates['applyFail'];
        $param = json_encode(array("phone"=> $phone),JSON_UNESCAPED_UNICODE);

        $res = static::sendSms($phone,$code,$param);

        if($res->Code == 'OK') {
            return true;
        }

        return false;
    }
//    public function changepw(Request $request){
//        $phone = $request->get('phone');
//        $newpassword = $request->get('newpassword');
//        if($newpassword && $phone){
//            $user = User::where('phone',$phone)->first();
//            if(isset($user)) {
//                $user->password = bcrypt($newpassword);
//                $user->save();
//                return response()->json(['state' => '0', 'message' => '密码修改成功']);
//            }
//            return response()->json(['state' =>'1','message'=>'密码修改失败']);
//        }
//        return response()->json(['state' =>'1','message'=>'密码修改失败']);
//    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgetGardener(Request $request)
    {
        $phone = $request->get('phone');
        $teacher = Teacher::where('tel',$phone)->first();
        if (!$teacher) return response()->json(['status'=>'0','message'=>'该手机号还未注册！']);

        $code = $this->templates['re_pwd'];
        $code_key = randomKeys(6);
        $param = json_encode(array("code"=> $code_key),JSON_UNESCAPED_UNICODE);

        $res = static::sendSms($phone,$code,$param);

        if($res->Code == 'OK') {
            Cache::put($phone.$code,$code_key,5);
            return response()->json(['status'=>1,'message'=>'发送成功']);
        }

        return response()->json(['status'=>0,'message'=>'发送失败']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
//    public function checkGardenerCode(Request $request)
//    {
//        $code = $request->get('code');
//        $phone = $request->get('phone');
//        $template = $this->templates['re_pwd'];
//        $code_key = Cache::get($phone.$template);
//        if($code == $code_key) {
//            //Cache::forget($phone.$template);
//            return response()->json(['status'=> 1,'message'=>'验证成功']);
//        }
//        else {
//            return response()->json(['status'=> 0,'message'=>'验证码错误或已过期']);
//        }
//    }

    public function checkGardenerCode($data)
    {
        $code = $data['code'];
        $phone = $data['phone'];
        $template = $this->templates['re_pwd'];
        $code_key = Cache::get($phone.$template);
        if($code == $code_key) {
            //Cache::forget($phone.$template);
            return $template;
        }
        return false;
    }

    public function checkUserCode($data)
    {
        $code = $data['code'];
        $phone = $data['phone'];
        $template = $this->templates['forget'];
        $code_key = Cache::get($phone.$template);
        if($code == $code_key) {
            //Cache::forget($phone.$template);
            return $template;
        }
        return false;
    }
}