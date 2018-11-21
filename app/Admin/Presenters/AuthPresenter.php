<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/17
 * Time: 15:34
 * Function:
 */

namespace App\Admin\Presenters;


use App\Models\Auth;

class AuthPresenter
{
    /**
     * @param $auth
     * @return string
     */
    public function status($auth)
    {
        $label = '<span class="label label-warning">错误</span>';

        if ($auth->status == Auth::AGREE) {
            $label = '<span class="label label-success">已同意</span>';
        }
        else if ($auth->status == Auth::REFUSE) {
            $label = '<span class="label label-danger">已拒绝</span>';
        }
        else if ($auth->status == Auth::APPLYING) {
            $label = '<span class="label label-warning">申请中</span>';
        }

        return $label;
    }

    public function operator($auth){
        $label = '<span class="label label-default">暂无</span>';
        if ($auth->operators){
            $oper_name = $auth->operators->name;
            $label = "<span class='label label-primary'>老师 - $oper_name</span>";
        }
        return $label;
    }

}