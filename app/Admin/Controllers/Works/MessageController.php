<?php

namespace app\Admin\Controllers\Works;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Tanmo\Admin\Facades\Admin;
use App\Models\School;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;

/**
 * @module 教师留言
 *
 * Class MessageController
 * @package app\Admin\Controllers\Works
 */
class MessageController extends Controller
{
    /**
     * @permission 留言列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('parent.name','parent_name');
            $searcher->like('teacher.name','teacher_name');
        });

        $header = '留言列表';
        if(Admin::user()->isAdmin()){
            $school_id = getShowSchoolId();
            if($school_id ) {
                $school = (new School())->where('id', '=', $school_id)->first();
                $header = $school->name . "--" . $header;
            }
            else{
                return view('admin::errors.no_school');
            }
        }else {
            $school_id = Admin::user()->school_id;
        }

        $start = request()->get('start');
        $end = request()->get('end');
        if(Admin::user()->role) {
            $data = getTeacherClassIds(Admin::user()->id);
            $messages = (new Message())->search($searcher)->where([['school_id',$school_id],['teacher_id',$data['id']]])->whereIn('class_id',$data['ids'])->TimeInterval($start,$end)->with(['parent.students.grade','parent.students.collective','teacher'])->latest()->paginate(10);
        }
        else {
            $messages = (new Message())->search($searcher)->where('school_id',$school_id)->TimeInterval($start,$end)->with(['parent.students.grade','parent.students.collective','teacher'])->latest()->paginate(10);
        }
        return view('admin::works.messages-messages',compact('messages','header'));
    }

    /**
     * @permission 删除留言
     *
     * @param Message $message
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Message $message)
    {
        if(Admin::user()->can('delete',$message)) {
            $message->delete();
            return response()->json(['status' => 1, 'message' => '成功']);
        }
        else {
            //session()->flash('error',collect(['title'=>['操作错误'],'message'=>['当前用户权限不足']]));
            return response()->json(['status' => 0, 'message' => '当前用户权限不足']);
        }
    }
}