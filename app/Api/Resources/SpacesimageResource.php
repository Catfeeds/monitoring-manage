<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;
use Illuminate\Support\Facades\Storage;

class SpacesimageResource extends JsonResource
{
	public function toArray($request)
	{

		return [
		    'name'=>$this->admin->name,
            'images'=>$this->images($this->image),
            'created_at'=>date('Y-m-d',strtotime($this->created_at)),
        ];
	}
	public function printf($file){
	    if($file)
	        return Storage::disk('public')->url($this->file);
	    return null;
    }
    public function images($images){
	    $arrs = explode(',',$this->image);
	    foreach ($arrs as $k =>$value){
	        $arrs[$k]=Storage::disk('public')->url($value);
        }
        return $arrs;
    }
}