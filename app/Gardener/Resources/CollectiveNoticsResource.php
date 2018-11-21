<?php

namespace App\Gardener\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class CollectiveNoticsResource extends JsonResource
{
    public function toArray($request)
    {
        $num = DB::table('notifications')->where('data->id',$this->id)->whereNotNull('read_at')->count();
        $content = mb_substr(str_replace('&nbsp;', '', strip_tags($this->content)),0,30,'utf-8');
        return [
            'id' => $this->id,
            'school_name' => $this->school->name,
            'title' => $this->title,
            'content' => mb_strlen($content,'UTF8')<=30?$content:$content.'....',
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'sum' => $this->sum_num,
            'read' => $num,
        ];
    }
}