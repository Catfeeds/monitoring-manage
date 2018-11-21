<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class VersionResource extends JsonResource
{
	public function toArray($request)
	{
		return [
		    'version_no' => $this->version_no,
            'download_url' => $this->download_url?Storage::disk('public')->url($this->download_url):null,
        ];
	}
}