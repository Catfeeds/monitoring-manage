<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/17
 * Time: 15:34
 * Function:
 */

namespace App\Admin\Presenters;


use App\Models\RelaxApply;
use Illuminate\Support\Facades\Storage;

class RelaxPresenter
{
    /**
     * @param $relaxApply
     * @return string
     */
    public function status($relaxApply)
    {
        $label = '<span class="label label-warning">错误</span>';

        if ($relaxApply->StatusText == 'agreed') {
            $label = '<span class="label label-success">已同意</span>';
        }

        else if ($relaxApply->StatusText == 'refused') {
            $label = '<span class="label label-danger">已拒绝</span>';
        }

        else if ($relaxApply->StatusText == 'applying') {
            $label = '<span class="label label-warning">申请中</span>';
        }
        else if($relaxApply->StatusText == 'cancel') {
            $label = '<span class="label label-default">已取消</span>';
        }
        return $label;
    }

}