<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PayConfig extends Model
{
    /**
     * @param $value
     * @return string
     */
    public function getAlipayCodeAttribute($value)
    {
        return Storage::disk('public')->url($value);
    }

    public function getWechatCodeAttribute($value)
    {
        return Storage::disk('public')->url($value);
    }
}
