<?php

namespace app\Gardener\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\School;
use App\Api\Resources\RecipeResource;

class RecipeController extends Controller
{
    /**
     * @param School $school
     * @return \Illuminate\Http\JsonResponse|\Tanmo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(School $school)
    {
        $this->authorize('show',$school);

//        $date = request()->get('date');
//        $date=date("Y-m-d",strtotime("$date last Monday"));
//        if(!$date) {
//            $date = date("Y-m-d",strtotime("last Monday"));
//        }
//        $recipe = Recipe::where([['school_id',$school->id],['begin_start',$date]])->first();
//        if($recipe) {
//            return api()->item($recipe,RecipeResource::class);
//        }
//        else {
//            return response()->json(['status'=>0,'messgae' => '该周食谱尚未添加!']);
//        }
        $recipe = Recipe::where('school_id',$school->id)->first();
        return api()->item($recipe,RecipeResource::class);
    }

//    public function update(Recipe $recipe)
//    {
//        $this->authorize('checkGrad',$recipe);
//
//        $content = request()->get('content');
//        $infos = json_decode($content, true);
//        if (json_last_error() != JSON_ERROR_NONE) {
//            response()->json(['status' => 0,'message' => '数据格式错误！']);
//        }
//
//        $course->content = $infos;
//        $course->save();
//
//        return response()->json(['status' => 1,'message' => '修改成功！']);
//    }
}