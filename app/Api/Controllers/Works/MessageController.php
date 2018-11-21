<?php

namespace app\Api\Controllers\Works;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageCover;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Auth;

class MessageController extends Controller
{
	public function store(Request $request)
    {
        $message = new Message();
        $message->school_id = $request->get('school_id');
        $message->teacher_id = $request->get('teacher_id');
        $message->user_id = Auth::user()->id;
        $message->class_id = $request->get('class_id');
        $content = expression($request->get('content'));
        $message->content = $content;
        $message->save();
        if($request->file('covers')) {
            foreach ($request->file('covers') as $file) {
                //file_put_contents('text.txt',var_export($file,1),FILE_APPEND);
                /**
                 * @var $file UploadedFile
                 */
                $path = $file->store('covers', 'public');
                $cover = new MessageCover(['path' => $path]);
                $message->covers()->save($cover);
            }

        }
        return api()->created();
    }
}