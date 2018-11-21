<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class QrcodeResource extends JsonResource
{
	public function toArray($request)
	{

		return [
		    'school_name'=>$this->school->name,
            'grade_name'=>$this->grade->name,
            'class_name'=>$this->name,
            'qrcode'=>Storage::disk('public')->url(str_replace('storage','',$this->qrcode)),
            'sn'=>$this->sn,
        ];
	}
}