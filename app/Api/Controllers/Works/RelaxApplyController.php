<?php

namespace app\Api\Controllers\Works;

use App\Http\Controllers\Controller;
use App\Models\RelaxApply;

use App\Models\RelaxApplyCover;
use Illuminate\Http\Request;
use Auth;
use App\Api\Resources\RelaxAppliesResource;
use App\Api\Resources\RelaxApplyDetailResource;
use Illuminate\Http\UploadedFile;

class RelaxApplyController extends Controller
{
    /**
     * @return \Tanmo\Api\Http\Response
     */
    public function index()
    {
        $school_id = request()->get('school_id');
        $relaxApplies = RelaxApply::where([['school_id',$school_id],['user_id',Auth::user()->id]])->with(['parent','student'])->latest()->get();
        return api()->collection($relaxApplies,RelaxAppliesResource::class);
    }

    /**
     * @param Request $request
     * @return \Tanmo\Api\Http\Response
     */
	public function store(Request $request)
    {
        $relaxApply = new RelaxApply();

        $relaxApply->school_id = $request->get('school_id');
        $relaxApply->teacher_id = $request->get('teacher_id');
        $relaxApply->student_id = $request->get('student_id');
        $relaxApply->class_id = $request->get('class_id');
        $relaxApply->user_id = Auth::user()->id;
        $relaxApply->reason = $request->get('reason');
        $relaxApply->begin = $request->get('begin');
        $relaxApply->end = $request->get('end');
        $relaxApply->date_num = $request->get('date_num');
        $relaxApply->type = $request->get('type');
        $relaxApply->save();

        if($request->file('covers')) {
            foreach ($request->file('covers') as $file) {
                /**
                 * @var $file UploadedFile
                 */
                $path = $file->store('covers', 'public');
                $cover = new RelaxApplyCover(['path' => $path]);
                $relaxApply->covers()->save($cover);
            }

        }

        return api()->created();
    }

    /**
     * @param RelaxApply $relaxApply
     * @return \Tanmo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(RelaxApply $relaxApply)
    {
        $this->authorize('show',$relaxApply);

        return api()->item($relaxApply, RelaxApplyDetailResource::class);
    }

    /**
     * @param RelaxApply $relaxApply
     * @return \Tanmo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function cancel(RelaxApply $relaxApply)
    {
        $this->authorize('cancel',$relaxApply);

        if($relaxApply->status !=  RelaxApply::APPLYING) {
            return response()->json(['status' => 0 ,'message' => '该申请已被确认，不可取消！']);
        }
        $relaxApply->status = RelaxApply::CANCEL;
        $relaxApply->save();

        return response()->json(['status' => 1 ,'message' => '提交成功！']);
    }
}