<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Tanmo\Admin\Models\Administrator;
use Tanmo\Search\Traits\Search;
class Press extends Model
{
    use Search;
    protected $table='press';
    protected $guarded=[];

    public function classify(){
        return $this->hasOne(Classify::class,'id','classify_id');
    }

    public function getBannerAttribute($value)
    {
        return Storage::disk('public')->url($value);
    }

    public function user(){
        return $this->hasOne(Administrator::class,'id','user_id');
    }
}
