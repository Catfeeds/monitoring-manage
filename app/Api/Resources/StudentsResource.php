<?php

namespace App\Api\Resources;

use App\Models\RelaxApply;
use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class StudentsResource extends JsonResource
{
	public function toArray($request)
	{
	    $this->load(['school','grade','collective.parents']);
		return [
		    'id' => $this->id,
            'school_id' => $this->school_id,
            'school_name' => $this->school_id?$this->school->name:null,
            'name' => $this->name,
            'sex' => $this->sex,
            'birthday' => $this->birthday?babyAge($this->birthday):null,
            'avatar' => $this->avatar,
            'grade' => $this->grade_id?$this->grade->name:null,
            'class' => $this->class_id?$this->collective->name:null,
            'class_id' => $this->class_id?$this->collective->id:null,
            'role' => $this->pivot->role,
            'leave_count' => (new RelaxApply())->countLeave($this->id),
        ];
	}

}