<?php

namespace App\Admin\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Camera;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Tanmo\Admin\Models\Administrator;
use Tanmo\Admin\Facades\Admin;
use Tanmo\Admin\Requests\AdministratorRequest;
use App\Models\School;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;
use App\Models\Order;

/**
 * @module 收费记录
 *
 * Class HomeController
 * @package App\Admin\Controllers
 */
class OrderController extends Controller
{
    /**
     * @permission 收费列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

       $orders = (new Order())->orderBy('created_at','desc')->paginate(10);
       return view('admin::orders.order',compact('orders'));
    }

    public function destroy(Order $order){

            $order->delete();
            return response()->json(['status' => 1, 'message' => '删除成功']);
    }






}