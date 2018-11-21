<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Order extends Model
{
    protected $table = 'orders';
    protected $guarded=[];

    const WAIT_PAY = 0;
    const FINISH = 1;
    const SUCCESS='success';
    public function submit(User $user,Charge $charge,$class_id,$school_id)
    {
        return DB::transaction(function () use ($user, $charge,$class_id,$school_id) {
            $order = $this->create([
                'user_id' => $user->id,
                'sn' => date('YmdHis') . $user->id . rand(10, 99),
                'price' => $charge->money,
                'school_id'=>$school_id,
                'charge_id'=> $charge->id,
                'class_id' => $class_id
            ]);
            $order->save();
            return $order;
        });
    }

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }
    public function charge(){
        return $this->hasOne(Charge::class,'id','charge_id');
    }
    public function school(){
        return $this->hasOne(School::class,'id','school_id');
    }


    public function getPriceAttribute($value)
    {
        return round($value / 100, 2);
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value * 100;
    }
}
