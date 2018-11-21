<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Tanmo\Search\Traits\Search;

class Help extends Model
{
    use Search;

    const USER = 1;
    const TEACHER = 2;

    /**
     * @param Builder $builder
     * @param $status
     * @return $this|void
     */
    public function scopeFilterStatus(Builder $builder, $status)
    {
        if($status || $status == '0') {
            return $builder->where('status', $status);
        }
        else return;
    }
}
