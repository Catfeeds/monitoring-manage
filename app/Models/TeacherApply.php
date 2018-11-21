<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TeacherApply extends Model
{
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function collective()
    {
        return $this->belongsTo(Collective::class,'class_id','id');
    }

    public function getStatusAttribute($value)
    {
        switch ($value) {
            case '0':
                $status = '待审核';
                break;
            case 1:
                $status = '已同意';
                break;
            case 2:
                $status = '已拒绝';
                break;
        }
        return $status;
    }
}
