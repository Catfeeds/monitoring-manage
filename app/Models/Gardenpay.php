<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tanmo\Search\Traits\Search;
use Illuminate\Support\Facades\Storage;
class Gardenpay extends Model
{
    use Search;
    protected $table = 'gardenpays';
    protected $guarded=[];
    public function getQrcodeAttribute($value)
    {

        return Storage::disk('public')->url($value);
    }
}
