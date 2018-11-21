<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $table="albums";
    protected $guarded=[];

    public function covers(){
        return $this->hasMany(AlbumCover::class,'album_id','id');
    }
}
