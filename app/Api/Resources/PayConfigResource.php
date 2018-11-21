<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PayConfigResource extends JsonResource
{
	public function toArray($request)
	{
        return [
            'title' => $this->title,
            'content' => $this->content,
            'wechat_code' => $this->wechat_code,
            'alipay_code' => $this->alipay_code,
            'bank_name' => $this->bank_name,
            'bank_card' => $this->bank_card,
            'bank_man' => $this->bank_man,
            'bank_place' => $this->bank_place,
            'release_man' => $this->release_man,
            'created_at' => optional($this->created_at)->toDateTimeString(),
        ];
	}
}