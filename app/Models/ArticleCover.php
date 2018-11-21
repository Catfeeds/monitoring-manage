<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleCover extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['path'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
