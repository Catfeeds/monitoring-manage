<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Collective;
use Tanmo\Search\Traits\Search;

class Grade extends Model
{
    use Search;

    public function collectives()
    {
        return $this->hasMany(Collective::class);
    }
}
