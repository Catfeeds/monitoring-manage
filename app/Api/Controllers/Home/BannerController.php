<?php

namespace app\Api\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Tanmo\Admin\Facades\Admin;
use App\Models\School;
use App\Http\Resources\BannersResource;

class BannerController extends Controller
{

    public function show($school_id)
    {
        $banners = (new Banner())->where('school_id','=',$school_id)->where('status','=',1)->orderBy('order','desc')->get();
        return api()->collection($banners,BannersResource::class);
    }
}