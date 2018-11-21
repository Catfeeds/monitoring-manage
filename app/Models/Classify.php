<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classify extends Model
{
    protected $table='classifies';
    protected $guarded=[];
    const ADMIN_SCHOOL=0;

    public function presses(){
        return $this->hasMany(Press::class,'classify_id','id');
    }
}
