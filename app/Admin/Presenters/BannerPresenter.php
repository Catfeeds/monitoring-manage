<?php
namespace App\Admin\Presenters;

use Illuminate\Support\Facades\Storage;

class BannerPresenter
{
    /**
     * @param $banner
     * @return mixed
     */
    public function showTitle($banner)
    {
        switch ($banner->link_type){
            case 'url':
                return $banner->link;
            case 'article':
                if ($res = $banner->articleTarget){
                    return $res->title;
                }
                return '<span class="badge bg-red">目标已失效</span>';
            case 'goods':
                if ($res = $banner->goodsTarget){
                    return $res->title;
                }
                return '<span class="badge bg-red">目标已失效</span>';
        }
    }

}
