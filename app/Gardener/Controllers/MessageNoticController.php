<?php

namespace app\Gardener\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MessageNotic;
use App\Gardener\Resources\CollectiveNoticsResource;
use App\Gardener\Resources\CollectiveNoticDetailResource;

class MessageNoticController extends Controller
{

    public function indexClass()
    {
        $teacher = auth()->user();

        $messageNotics = MessageNotic::where('admin_id',$teacher->admin_id)->latest()->get();
        return api()->collection($messageNotics, CollectiveNoticsResource::class);
    }

    public function detailClass(MessageNotic $messageNotic)
    {
        $this->authorize('showGrad',$messageNotic);

        return api()->item($messageNotic, CollectiveNoticDetailResource::class);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function storeClass(Request $request)
    {
        $teacher = auth()->user();

        $notic = new MessageNotic();
        $notic->title = $request->get('title');
        $notic->content = $request->get('content');
        $notic->scope =  MessageNotic::COLLECTIVE;
        $notic->admin_id = $teacher->admin_id;
        $notic->collection_ids = $request->get('classes');
        $notic->school_id = $teacher->school_id;

        $notic->save();

        return response()->json(['status' => 1,'message' => '发送成功！']);
    }
}