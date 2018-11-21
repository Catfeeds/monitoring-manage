<?php

namespace App\Api\Controllers\Space;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlbumResource;
use App\Http\Resources\FileResource;
use App\Models\Album;
use App\Models\Camera;
use App\Models\Gardenpay;
use App\Models\Space;
use Illuminate\Http\Request;
use Tanmo\Admin\Facades\Admin;
use App\Models\School;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;
use App\Models\Grade;
use App\Models\Collective;
use App\Models\Teacher;
use App\Api\Resources\SpacesfileResource;
use App\Api\Resources\SpacesimageResource;
use App\Api\Resources\GardenpayResource;
class SpaceController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->user = Auth()->user();
    }

    public function file(){
        $class_id = request('class_id');
        $space = (new Space())->where('class_id',$class_id)->where('file','!=',null)->get();
        return api()->collection($space,SpacesfileResource::class);
    }
    public function image(){
        $class_id = request('class_id');
        $space = (new Space())->where('class_id',$class_id)->where('image','!=',null)->get();
        return api()->collection($space,SpacesimageResource::class);
    }

    public function albumlist($class_id){
        $album = Album::where('class_id',$class_id)->get();
        return api()->collection($album,AlbumResource::class);

    }

    public function albumdetail(Album $album){
        $covers =$album->covers;
        $data =[];
        foreach ($covers as $key => $cover){
            $data[$cover->sn]['path'][] =$cover->path;
            $data[$cover->sn]['sn'] =$cover->sn;
        }
        $data = array_values($data);
        return response()->json($data);
    }

    public function spacefile($class_id){
        $files = Space::where('class_id',$class_id)->get();
        return api()->collection($files,FileResource::class);
    }

    public function pay(){
        $class_id = request('class_id');
        $gardentpay = (new Gardenpay())->where('class_id',$class_id)->first();
        return api()->item($gardentpay,GardenpayResource::class);
    }

    public function imgsadd(Request $request,Album $album){
        $imgs = $request->file('imgs');
        $sn = date('Y-m-d');
        if($imgs) {
            foreach ($imgs as $img) {
                $path = $img->store('space', 'public');
                $album->covers()->create(['sn' => $sn, 'path' => $path]);
            }
            return response()->json(['status'=>1,'msg'=>'添加成功']);
        }
        return response()->json(['status'=>0,'msg'=>'添加失败']);
    }
}