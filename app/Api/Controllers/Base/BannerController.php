<?php

namespace app\Api\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Models\PlatBanner;
use App\Http\Resources\BannersResource;

class BannerController extends Controller
{
    public function show()
    {
        $banners = (new PlatBanner())->where('status','=',1)->orderBy('order','desc')->get();
        return api()->collection($banners,BannersResource::class);
    }
}