<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zan extends Model
{
    protected $table = 'zans';

    protected $guarded=[];

    public function article()
    {
        return $this->hasOne(Article::class,'id','article_id');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function teacher(){
        return $this->hasOne(Teacher::class,'id','user_id');
    }
}
