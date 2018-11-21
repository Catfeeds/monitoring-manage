<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class FeedbackCover extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['path'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function feedback()
    {
        return $this->belongsTo(Feedback::class,'id','feedback_id');
    }

    public function getPathAttribute($value){
        return Storage::disk('public')->url($value);
    }
}
