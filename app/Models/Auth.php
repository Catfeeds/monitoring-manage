<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tanmo\Search\Traits\Search;

class Auth extends Model
{
    use Search;
    protected $guarded = [];

    const APPLYING = 1;
    const AGREE = 2;
    const REFUSE = 3;

    protected $statusMap = [
        self::APPLYING => '申请中',
        self::AGREE => '已同意',
        self::REFUSE => '已拒绝',
        ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function collective(){
        return $this->belongsTo(Collective::class,'class_id','id');
    }

    public function operators(){
        return $this->belongsTo(Teacher::class,'operator','id');
    }





}
