<?php

namespace app\Api\Controllers\Works;

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
        $this->authorize('check',$school);

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
}