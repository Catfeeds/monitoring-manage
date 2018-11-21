<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Tanmo\Search\Traits\Search;

class Message extends Model
{
    use Search;

    protected $table = 'messages';
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class,'teacher_id','id');
    }

    /**
     * @param Builder $builder
     * @param $start
     * @param $end
     * @return $this|void
     */
    public function scopeTimeInterval(Builder $builder, $start,$end)
    {
        if($start && $end) {
            return $builder->whereBetween('created_at', [$start,$end]);
        }
        else if($start) {
            return $builder->where('created_at', '>=' ,$start);
        }
        else if($end) {
            return $builder->where('created_at', '<=' ,$end);
        }
        else return;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function covers()
    {
        return $this->hasMany(MessageCover::class);
    }
}
