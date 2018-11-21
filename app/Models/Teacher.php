<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tanmo\Admin\Models\Administrator;
use Tanmo\Search\Traits\Search;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Teacher extends Authenticatable implements JWTSubject
{
    use Search, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'teachers';
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAvatarAttribute($value)
    {
        if ($value)
            return Storage::disk('public')->url($value);

        return admin_asset('/AdminLTE/dist/img/user2-160x160.jpg');;
    }

    public function school(){
        return $this->hasOne(School::class,'id','school_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function collective(){
        return $this->belongsToMany(Collective::class,'teacher_class','teacher_id','class_id');
    }

    public function admin(){
        return $this->hasOne(Administrator::class,'id','admin_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function articles(){
        return $this->hasMany(Article::class,'user_id','id')->orderBy('created_at','desc')->where('type',1);
    }

    public function labels(){
        return $this->hasMany(UserLabel::class,'user_id','id')->where('type',1);
    }

}
