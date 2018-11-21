<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tanmo\Search\Traits\Search;

class Course extends Model
{
    use Search;

    protected $casts = [
        'content' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function collective()
    {
        return $this->belongsTo(Collective::class,'class_id','id');
    }
}
