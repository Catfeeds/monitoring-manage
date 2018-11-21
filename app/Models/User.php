<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable {
        notify as protected laravelNotify;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    const VISITOR = 1;
    const PARENT = 2;

    protected $gradeMaps = [
        self::VISITOR => '游客',
        self::PARENT => '家长',
    ];

    /**
     * @param $value
     * @return string
     */
    public function getWayAttribute($value)
    {
        if($value == 0) {
            return '录入';
        }
        else {
            return '老师邀请';
        }
    }

    /**
     * @param $value
     * @return string
     */
    public function getSexAttribute($value)
    {
        switch ($value){
            case 1:
                return '男';
                break;
            case 2:
                return '女';
                break;
            case 3:
                return '保密';
                break;
        }
    }

    /**
     * @param $value
     * @return string
     */
    public function getGradesAttribute($value)
    {
        return $this->gradeMaps[$value];

    }


    /**
     * @param $value
     * @return string
     */
    public function getStatusAttribute($value)
    {
        switch ($value){
            case 1:
                return '状态良好';
                break;
            case 2:
                return '状态还行';
                break;
            case 3:
                return '状态不好';
                break;
        }
    }

    /**
     * @param $instance
     */
    public function notify($instance)
    {
        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function students()
    {
        return $this->belongsToMany(Student::class,'student_parent','parent_id','student_id')->withPivot('role','contact');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles(){
        return $this->hasMany(Article::class,'user_id','id')->orderBy('created_at','desc')->where('type',0);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function schools(){

        return $this->belongsToMany(School::class,'school_parent','parent_id','school_id');
    }

    /**
     * @param $student
     * @return mixed
     */
    public function role($student){
        return DB::table('student_parent')->select('role')->where('student_id',$student)->where('parent_id',$this->id)->first()->role;
    }



    /**
     * @param $value
     * @return string
     */
    public function getAvatarAttribute($value)
    {
        if ($value){
            return Storage::disk('public')->url($value);
        }else{
            return config('app.url').'/images/user.png';
        }

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function collectives()
    {
        return $this->belongsToMany(Collective::class,'collective_parent','parent_id','class_id')->withPivot('expire_at');
    }

    public function maillists(){
        return $this->belongsToMany(User::class,'maillists','user_id','friend_id')->withPivot('note');
    }

    public function orders(){
        return $this->hasMany(Order::class,'user_id','id');
    }

    public function labels(){
        return $this->hasMany(UserLabel::class,'user_id','id')->where('type',0);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userCode()
    {
        return $this->hasMany(UserCode::class,'user_id','id');
    }
}
