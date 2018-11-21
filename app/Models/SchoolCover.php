<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SchoolCover extends Model
{
    /**
     * @var array
     */
    protected $table="school_covers";
    protected $guarded = [];
    public function getPathAttribute($value)
    {
        return Storage::disk('public')->url($value);
    }
}
