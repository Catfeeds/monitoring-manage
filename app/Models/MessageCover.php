<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageCover extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['path'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
