<?php

namespace App\Api\Controllers\Feedback;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\FeedbackCover;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
class FeedbackController extends Controller
{
    //用户上传反馈
    public function store(Request $request,$school_id){
        $date = ['state'=>1,'msg' => '上传失败'];
       //验证
        $user = auth('api')->user();
      //  return $user;
        if( $user  && $request->get('content')){
            //逻辑
            $feedback = (new Feedback());
            $feedback->name=$user->name;
            $feedback->content=$request->get('content');
            $feedback->user_id=$user->id;
            $feedback->school_id = $school_id;

            $feedback->save();
            if($request->file('covers')) {
                foreach ($request->file('covers') as $file) {
                    file_put_contents('text.txt',var_export($file,1),FILE_APPEND);
                    /**
                     * @var $file UploadedFile
                     */
                    $path = $file->store('covers', 'public');
                    $cover = new FeedbackCover(['path' => $path]);
                    $feedback->covers()->save($cover);
                }
            }

            //返回数据
            $date['state']=0;
            $date['msg']='谢谢您宝贵的意见';
        }

        return response()->json($date);
    }

}
