<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tanmo\Admin\Models\Administrator;
use App\Models\Collective;

class Homework extends Model
{
    public function adminUser()
    {
        return $this->belongsTo(Administrator::class,'admin_id','id');
    }

    public function collective()
    {
        return $this->belongsTo(Collective::class,'class_id','id');
    }
}
