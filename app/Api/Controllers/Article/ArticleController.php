<?php

namespace App\Api\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentsResource;
use App\Http\Resources\PressDetailResource;
use App\Http\Resources\PressResource;
use App\Models\Classify;
use App\Models\Label;
use App\Models\Press;
use App\Models\UserLabel;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Http\Resources\ArticlesResource;
use App\Models\Comment;
use App\Models\ArticleCover;
use Illuminate\Http\UploadedFile;
use App\Models\Zan;
use Illuminate\Support\Facades\Storage;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;
class ArticleController extends Controller
{

    public function index($school_id)
    {

        $articles = (new Article())->where('school_id','=',$school_id)->orderBy('created_at','desc')->get();
        return api()->collection($articles,ArticlesResource::class);
    }

    public function show(Article $article){
        return api()->item($article,ArticlesResource::class);
    }

    public function store(Request $request,$school_id){
        $user = auth('api')->user();


        if($user) {
            $article = new Article();
            $article->content = $request->get('content');
            $article->label = $request->get('label');
            $article->user_id = $user->id;
            $article->school_id = $school_id;
            if($request->file('movie')){
                $movie_path = $request->file('movie')->store('movie','public');
                $article->movie = Storage::disk('public')->url($movie_path);
                $ffmpeg = FFMpeg::create(array(
                    'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
                    'ffprobe.binaries' => '/usr/bin/ffprobe',
                    'timeout'          => 3600, // The timeout for the underlying process
                    'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
                ));
                $video = $ffmpeg->open(Storage::disk('public')->url($movie_path));
                $frame = $video->frame(TimeCode::fromSeconds(1));
                $path='/imgs/'.date('YmdHis').$user->id.'.jpg';
                $frame->save('storage'.$path);
                $article->first_movie = Storage::disk('public')->url($path);
            }
            $article->save();
            if($request->file('covers')) {
                foreach ($request->file('covers') as $file) {
                    //file_put_contents('text.txt',var_export($file,1),FILE_APPEND);
                    /**
                     * @var $file UploadedFile
                     */
                    $path = $file->store('covers', 'public');
                    $cover = new ArticleCover(['path' => $path]);
                    $article->covers()->save($cover);
                }
           
            }

            return response()->json(['state' => '0', 'message' => '添加成功']);
        }
        return response()->json(['state' => '1', 'message' => '添加失败']);

    }

    public function destroy(Article $article){
        if(auth('api')->user()->can('delete',$article)) {
            $article->delete();
            return response()->json(['state' => '0', 'message' => '删除成功']);
        }
        return response()->json(['state' => '1', 'message' => '删除失败']);
    }

    public function zan(Article $article){
        $user = auth('api')->user();
        if($user) {
            $zan = new Zan(['user_id'=>$user->id]);
            $article->zans()->save($zan);
            return response()->json(['state' => '0', 'message' => '点赞成功']);
        }
        return response()->json(['state' => '1', 'message' => '点赞失败']);
    }

    public function unzan(Article $article){
        $user = auth('api')->user();
        if($user) {
            $article->zan($user->id)->delete();
            return response()->json(['state' => '0', 'message' => '操作成功']);
        }
        return response()->json(['state' => '1', 'message' => '操作失败']);
    }

    public function comment(Article $article,Request $request){


        $user = auth('api')->user();
        if($user && $request->get('content')) {
            $comment = new Comment(['user_id'=>$user->id,'content'=>$request->get('content')]);

            $article->comments()->save($comment);
            return response()->json(['state' => '0', 'message' => '评论成功']);
        }
        return response()->json(['state' => '1', 'message' => '评论失败']);
    }

    public function comments(Article $article){
        $comments = $article->comments;
        return api()->collection($comments,CommentsResource::class);
    }

    public function myarticles(){

        $user = auth('api')->user();
        if($user){
            return api()->collection($user->articles,ArticlesResource::class);
        }
    }

    public function category($school_id){
        return response()->json((new Classify())->where('school_id',$school_id)->get(['id','name']));
    }
    public function press($category_id){
        return api()->collection(Press::where('classify_id',$category_id)->get(),PressResource::class);
    }
    public function search(Request $request){
        $content = $request->get('content');
        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->like('content');
        });
        return api()->collection((new Press())->search($searcher)->get(),PressResource::class);
    }

    public function detailpress(Press $press){
        $press->look_count+=1;
        $press->save();
        return api()->item($press,PressDetailResource::class);
    }

    public function labels(){
        $user =  $user = auth('api')->user();
        $labels_set = [];
        $labels = Label::get(['name']);
        foreach ($labels as $label){
            $labels_set[] = $label->name;
        }
        $user_labels = $user->labels;
        foreach ($user_labels as $label){
            $labels_set[] = $label->name;
        }
        return response()->json($labels_set);
    }

    public function userlabels(){
        $user =  $user = auth('api')->user();
        $labels_set = [];

        $user_labels = $user->labels;
        foreach ($user_labels as $label){
            $labels_set[] = [
                'id' =>$label->id,
              'name'=>  $label->name
            ];
        }
        return response()->json($labels_set);
    }

    public function dellabel(UserLabel $userLabel){
        $userLabel->delete();
        return response()->json(['status'=>1,'msg'=>'删除成功']);
    }

    public function addlabel(Request $request){
        if(auth('api')->user()->labels->count()<=8) {
            UserLabel::create(['name' => $request->get('name'), 'user_id' => auth('api')->user()->id]);
            return response()->json(['status' => 1, 'msg' => '添加成功']);
        }
        return response()->json(['status' => 0, 'msg' => '添加数量已经达到上限']);
    }


}