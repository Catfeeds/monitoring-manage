<?php

namespace app\Gardener\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RelaxApply;
use App\Models\Collective;
use Illuminate\Http\Request;
use App\Api\Resources\RelaxAppliesResource;
use App\Api\Resources\RelaxApplyDetailResource;

class RelaxApplyController extends Controller
{
    /**
     * @param Collective $collective
     * @return \Tanmo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
	public function index(Collective $collective)
    {
        $this->authorize('checkGrad',$collective);
        $teacher = auth()->user();

        if ($status = request()->get('status')) {
            if($status == 'finish') {
                $relaxApplies = RelaxApply::where('teacher_id',$teacher->id)->filterStatus(['agreed','refused'])->with(['parent','teacher','collective'])->latest()->get();
            }
            else {
                $relaxApplies = RelaxApply::where('teacher_id',$teacher->id)->filterStatus([$status])->with(['parent','teacher','collective'])->latest()->get();
            }
        }
        else {
            $relaxApplies = RelaxApply::where('teacher_id',$teacher->id)->with(['parent','teacher','collective'])->latest()->get();
        }

        return api()->collection($relaxApplies, RelaxAppliesResource::class);
    }

    /**
     * @param RelaxApply $relaxApply
     * @return \Tanmo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(RelaxApply $relaxApply)
    {
        $this->authorize('showGrad',$relaxApply);

        return api()->item($relaxApply, RelaxApplyDetailResource::class);
    }

    /**
     * @param RelaxApply $relaxApply
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function agree(RelaxApply $relaxApply)
    {
        $this->authorize('showGrad',$relaxApply);

        if($relaxApply->status_text != 'applying') {
            return response()->json(['status' => 0, 'message' => '错误的操作！']);
        }
        $relaxApply->status = RelaxApply::AGREED;
        $relaxApply->save();

        return response()->json(['status' => 1, 'message' => '已同意']);
    }

    public function refuse(RelaxApply $relaxApply)
    {
        $this->authorize('showGrad',$relaxApply);
        mlog('text',$relaxApply->status);

        if($relaxApply->status_text != 'applying') {
            return response()->json(['status' => 0, 'message' => '错误的操作！']);
        }
        $relaxApply->status = RelaxApply::REFUSED;
        $relaxApply->save();

        return response()->json(['status' => 1, 'message' => '已拒绝']);
    }
}