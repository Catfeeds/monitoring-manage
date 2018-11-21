<?php

namespace App\Api\Resources;

use App\Models\RelaxApply;
use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class StudentDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'school_id' => $this->school_id,
            'school_name' => $this->school_id?$this->school->name:null,
            'name' => $this->name,
            'sex' => $this->sex,
            'birthday' => date('Y-m-d',strtotime($this->birthday)),
            'age' => $this->birthday?babyAge($this->birthday):null,
            'avatar' => $this->avatar,
            'grade' => $this->grade_id?$this->grade->name:null,
            'class' => $this->class_id?$this->collective->name:null,
            'teachers' => $this->class_id?TeacherResource::collection($this->collective->teachers):null,
            'role' => $this->pivot->role,
            'expire_at' => $this->class_id?$this->collective->parents()->where('parent_id',Auth::user()->id)->first()->pivot->expire_at:null,
            'is_expire' => $this->class_id?strtotime($this->collective->parents()->where('parent_id',Auth::user()->id)->first()->pivot->expire_at)>strtotime(date('Y-m-d H:i:s'))?1:0:null,
            'leave_count' => (new RelaxApply())->countLeave($this->id),
        ];
    }
}