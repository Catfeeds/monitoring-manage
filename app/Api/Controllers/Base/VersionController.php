<?php

namespace app\Api\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Models\Version;
use Illuminate\Http\Request;
use App\Api\Resources\VersionResource;
use App\Api\Resources\VersionGradResource;

class VersionController extends Controller
{
    public function version(Request $request){
        $version = Version::where('status', '=',$request->get('status'))->first();
        if($version) {
            return api()->item($version,VersionResource::class);
        }
        else {
            return response()->json(['status' => 0,'message' => '请选择App类型！']);
        }
    }

    public function gard(Request $request){
        $version = Version::first();

        return api()->item($version,VersionGradResource::class);
    }
}