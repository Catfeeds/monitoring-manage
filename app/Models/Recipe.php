<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Recipe extends Model
{
    protected $casts = [
        'content' => 'array',
        'tags' => 'array',
    ];

    public function scopeTimeInterval(Builder $builder, $start,$end)
    {
        if($start && $end) {
            return $builder->whereBetween('begin_start', [$start,$end]);
        }
        else if($start) {
            return $builder->where('begin_start', '>=' ,$start);
        }
        else if($end) {
            return $builder->where('begin_start', '<=' ,$end);
        }
        else return;
    }
}