<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class AlbumCover extends Model
{
    protected $table="album_covers";
    protected $guarded=[];

    public function getPathAttribute($value)
    {
        return Storage::disk('public')->url($value);
    }
}
