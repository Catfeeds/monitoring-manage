<?php

namespace App\Gardener\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class HomeworkResource extends JsonResource
{
    public function toArray($request)
    {
        $content = mb_substr(str_replace('&nbsp;', '', strip_tags($this->content)),0,10,'utf-8');
        return [
            'homework_id' => $this->id,
            'title' => $this->title,
            'week' => get_date_week(strtotime($this->created_at)),
            'content' => mb_strlen($content,'UTF8')<=10?$content:$content.'....',
            'month' => date('m',strtotime($this->created_at)),
            'date' => date('d',strtotime($this->created_at)),
            'read' => $this->end_at,
            'sum' => $this->spend_sum,
            'read_sum' => $this->read_sum,
        ];
    }
}