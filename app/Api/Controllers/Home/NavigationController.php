<?php

namespace app\Api\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Navigation;
use Illuminate\Http\Request;
use Tanmo\Admin\Facades\Admin;
use App\Models\School;
use App\Http\Resources\NavigationResource;
/**
 * @module 导航
 *
 * Class NavigationController
 * @package app\Admin\Controllers\Home
 */
class NavigationController extends Controller
{
    public function show($school_id){
        $navigations = (new Navigation())->where('school_id','=',$school_id)->where('status','=',1)->orderBy('order','desc')->get();
        return api()->collection($navigations,NavigationResource::class);
    }
}