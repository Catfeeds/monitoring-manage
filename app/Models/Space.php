<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tanmo\Admin\Models\Administrator;
use Tanmo\Search\Traits\Search;

class Space extends Model
{
    use Search;
    protected $table='spaces';
    protected $guarded=[];
    const DOC_TYPE = 1 ;
    const XLS_TYPE = 2 ;
    public function admin(){
        return $this->hasOne(Administrator::class,'id','admin_id');
    }
    public function collective(){
        return $this->hasOne(Collective::class,'id','class_id');
    }

}
