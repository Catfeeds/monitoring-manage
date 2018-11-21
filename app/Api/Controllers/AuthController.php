<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/5/005
 * Time: 15:24
 */
namespace App\Api\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Packages\alipay\Alipay;
use Illuminate\Http\UploadedFile;
use Auth;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api',['except' => ['login','register_one','register_two','rePwd']]);
    }

    /**
     * 检查验证码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register_one(Request $request){
        $data = [
            'phone' => $request->phone,
            'code' => $request->code,
        ];
        $checkCode = (new SendMessage())->checkRegisterCode($data);

        if ( !$checkCode ) {
            return response()->json(['status'=>0,'message'=>'验证码错误']);
        }else{
            return response()->json(['status'=>1,'message'=>'检查通过']);
        }
    }

    /**
     * 用户（家长）注册
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register_two(Request $request){
        $data = [
            'name' => '游客'.$request->phone,
            'phone' => $request->phone,
            'password' => $request->password,
//            'code' => $request->code,
        ];
        foreach ($data as $key => $val){
            if (!$val) return response()->json(['status'=>0,'message'=>'信息不完整']);
        }

        $data['password'] = bcrypt($data['password']);

        User::create($data);

        return response()->json(['status'=>1,'message'=>'恭喜注册成功！']);

    }


    public function login(Request $request){
        $params = array(
            'phone'=>$request->get('phone'),
            'password'=>$request->get('password')
        );
        return ($token = Auth::guard('api')->attempt($params))
            ? response([
                'state'=>'0',
                'access_token' => $token,
                'token_type' => 'bearer',
                'identity' => auth('api')->user()->grades,
            ])
            : response(['state'=>'1','error' => '账号或密码错误']);
    }


    public function identification(Request $request){
        $user = auth('api')->user();
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function show(){
        $user = auth('api')->user();
        $user->birthday = date('Y-m-d',$user->birthday);
        return response( $user);
    }



    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = auth('api')->user();

        if($request->file('avatar')) {
            $path = $request->file('avatar')->store('users', 'public');
            $user->avatar = $path;
        }

        if($request->get('name')) {
            $user->name = $request->name;
        }

        if($request->get('birthday')) {
            $user->birthday = strtotime($request->birthday);
        }

        $res = $user->save();

        if($res) {
            return response()->json(['status'=>1,'message' => '修改成功']);
        }
        else {
            return response()->json(['status'=>0,'message' => '修改失败']);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function rePwd(Request $request)
    {
        $phone = $request->get('phone');
        $code = $request->get('code');
        $pwd = $request->get('password');

        $sendMessage = new SendMessage();
        $data['phone'] = $phone;
        $data['code'] = $code;
        $rsg = $sendMessage->checkUserCode($data);
        if(!$rsg) {
            return response(['status' => 0,'message' => '校验失败！请重新操作！' ]);
        }
        $user = User::where('phone',$phone)->first();
        if(!$user) {
            return response(['status' => 0,'message' => '校验失败！请重新操作！' ]);
        }
        $user->password = bcrypt($pwd);
        $user->save();
        Cache::forget($phone.$rsg);
        return response(['status' => 1,'message' => '重置密码成功！' ]);
    }


//    /**
//     * 生成APP支付订单信息
//     * @param string $orderId   商品订单号
//     * @param string $subject   支付商品的标题
//     * @param string $body      支付商品描述
//     * @param float $total_amount  商品总支付金额
//     * @param int $expire       支付过期时间，分
//     * @return bool|string  返回支付宝签名后订单信息，否则返回false
//     */
//    public function pay(){
//        $pay = new Alipay();
//        return $pay->generateOrder('1245683120220','老干妈','好吃的','0.01','5d');
//    }
}