<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
  protected $table = 'charges';

    public function getMoneyAttribute($value)
    {
        return round($value / 100, 2);
    }
    public function setMoneyAttribute($value)
    {
        $this->attributes['money'] = $value * 100;
    }
}
