<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tanmo\Search\Traits\Search;

class Collective extends Model
{
    use Search;
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id', 'id');
    }

    public function parents()
    {
        return $this->belongsToMany(User::class,'collective_parent','class_id','parent_id')->withPivot('expire_at');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class,'teacher_class','class_id','teacher_id');
    }

    public function gardenpay(){
        return $this->hasOne(Gardenpay::class,'class_id','id');
    }
}