<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class VersionGradResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'vergrad_no' => $this->vergrad_no,
            'grad_url' => $this->grad_url?Storage::disk('public')->url($this->grad_url):null,
        ];
    }
}