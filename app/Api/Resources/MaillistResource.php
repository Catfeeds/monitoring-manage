<?php

namespace App\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MaillistResource extends JsonResource
{
    public function toArray($request)
    {
        $teachers=[];
        foreach ($this->collective->teachers as $key=>$teacher){
            $res['id'] = $teacher->id;
            $res['name'] = $teacher->name;
            $res['tel'] = $teacher->tel;
            $res['avatar'] = $teacher->avatar;
            $teachers[$key]=$res;
        }
        $class=$this->collective->grade->name.$this->collective->name;
       return [
           'school_id' => $this->school_id,
           'class_id'=>$this->collective->id,
           'teachers'=>$teachers,
           'class'=>$class,
       ];
    }
}