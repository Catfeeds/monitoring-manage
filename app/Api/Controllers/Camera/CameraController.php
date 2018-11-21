<?php

namespace App\Api\Controllers\Camera;

use App\Http\Controllers\Controller;
use App\Models\Camera;
use Illuminate\Http\Request;
use Tanmo\Admin\Facades\Admin;
use App\Models\School;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;
use App\Models\Grade;
use App\Api\Resources\CameraResource;

class CameraController extends Controller
{
   public function show($class_id){
       $camers = (new Camera())->where('class_id','=',$class_id)->with('collective.grade')->get();
       return api()->collection($camers,CameraResource::class);
   }
}