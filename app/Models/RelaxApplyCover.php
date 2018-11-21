<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelaxApplyCover extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['path'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function relaxApply()
    {
        return $this->belongsTo(RelaxApply::class,'apply_id','id');
    }
}
