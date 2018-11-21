<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;
use Illuminate\Support\Facades\Storage;

class SpacesfileResource extends JsonResource
{
	public function toArray($request)
	{

		return [
		    'name'=>$this->admin->name,
            'file'=>$this->printf($this->file),
            'type'=>$this->type,
            'created_at'=>date('Y-m-d',strtotime($this->created_at)),
        ];
	}
	public function printf($file){
	    if($file)
	        return Storage::disk('public')->url($this->file);
	    return null;
    }
}