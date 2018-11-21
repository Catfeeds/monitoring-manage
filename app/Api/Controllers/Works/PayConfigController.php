<?php

namespace app\Api\Controllers\Works;

use App\Http\Controllers\Controller;
use App\Models\PayConfig;
use App\Models\School;
use App\Api\Resources\PayConfigResource;

class PayConfigController extends Controller
{
	public function show(School $school) {
        $this->authorize('check',$school);
        $payConfig = PayConfig::where('school_id',$school->id)->first();

        if(!$payConfig) return response()->json(['status'=>0,'message'=>'暂无数据']);

        return api()->item($payConfig,PayConfigResource::class);
    }
}