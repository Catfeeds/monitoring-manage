<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;
use Illuminate\Support\Facades\Storage;

class GardenpayResource extends JsonResource
{
	public function toArray($request)
	{

		return [
		    'name'=>$this->name,
            'account'=>$this->account,
            'qrcode'=>$this->qrcode,
        ];
	}

}