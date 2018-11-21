<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';
    public function user(){

        return $this->hasOne(User::class,'id','user_id');
    }

    //todo
    public function teacher(){

        return $this->hasOne(Teacher::class,'id','user_id');
    }

    public function zans(){
        return $this->hasMany(Zan::class,'article_id','id');
    }

    public function comments(){
        return $this->hasMany(Comment::class,'article_id','id')->orderBy('created_at','desc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function covers()
    {
        return $this->hasMany(ArticleCover::class);
    }

    //用户是否有赞
    public function zan(){
        $user = auth('api')->user();
        $teacher = auth('teacher')->user();
        if($user){
            return $this->hasOne(Zan::class)->where('user_id',$user->id);
        }else{
            return $this->hasOne(Zan::class)->where('user_id',$teacher->id);
        }
    }


}
