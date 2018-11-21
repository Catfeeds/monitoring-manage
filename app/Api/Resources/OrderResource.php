<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
	public function toArray($request)
	{

		return [
		    'id' => $this->id,
		    'sn' => $this->sn,
            'user_name'=>$this->user->name,
            'school_name' => $this->school->name,
            'price' =>$this->price,
            'charge_day'=>$this->charge->time,
            'created_at'=>optional($this->created_at)->toDateTimeString(),
        ];
	}
}