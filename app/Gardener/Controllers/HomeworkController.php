<?php

namespace app\Gardener\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Home;
use Illuminate\Http\Request;
use App\Models\Homework;
use App\Gardener\Resources\HomeworkResource;
use App\Gardener\Resources\HomeworkDetailResource;

class HomeworkController extends Controller
{
    /**
     * @return \Tanmo\Api\Http\Response
     */
    public function index()
    {
        $teacher = auth()->user();

        $homework = Homework::where('admin_id',$teacher->admin_id)->latest()->get();
        return api()->collection($homework, HomeworkResource::class);
    }

    /**
     * @param Homework $homework
     * @return \Tanmo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Homework $homework)
    {
        $this->authorize('showGrad',$homework);

        return api()->item($homework, HomeworkDetailResource::class);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $teacher = auth()->user();

        $homework = new Homework();
        $homework->title = $request->get('title');
        $homework->content = $request->get('content');
        $homework->class_id =  $request->get('class_id');
        $homework->admin_id = $teacher->admin_id;
        $homework->school_id = $teacher->school_id;
        $homework->end_at = $request->get('end_at');

        $homework->save();

        return response()->json(['status' => 1,'message' => '发布成功！']);
    }
}