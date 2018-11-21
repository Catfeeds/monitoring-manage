<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tanmo\Search\Traits\Search;
use Illuminate\Support\Facades\Storage;
class Student extends Model
{
    use Search, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'students';
    protected $sex = ['1'=>'女','2'=>'男'];
    protected $guarded =[];

    /**
     * @param $value
     * @return string
     */
    public function getAvatarAttribute($value)
    {
        if ($value)
            return Storage::disk('public')->url($value);

        return config('app.url').'/images/student.jpg';
    }




    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function collective()
    {
        return $this->belongsTo(Collective::class,'class_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
        public function parents()
    {
        return $this->belongsToMany(User::class,'student_parent','student_id','parent_id')->withPivot('role','contact');
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getSexAttribute($value)
    {
        return $this->sex[$value];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

}
