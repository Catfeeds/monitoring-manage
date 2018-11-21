<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tanmo\Admin\Models\Administrator;
use Tanmo\Search\Traits\Search;

/**
 * Class MessageNotic
 * @package App\Models
 */
class MessageNotic extends Model
{
    use search;

    const SCHOOL = 1;
    const COLLECTIVE = 2;

    protected $scopes = [
        self::SCHOOL => 'school',
        self::COLLECTIVE => 'collective',
    ];

    protected $casts = [
        'collection_ids' => 'array'
    ];

    /**
     * @return mixed
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function adminUser()
    {
        return $this->belongsTo(Administrator::class,'admin_id','id');
    }

    /**
     * @return mixed
     */
    public function getScopeTextAttribute()
    {
        return $this->scopes[$this->scope];
    }

    public function scopeFilterStatus(Builder $builder, $status)
    {
        if (array_key_exists($status, $this->scopes)) {
            return $builder->where('scope', $status);
        }

        $value = array_flip($this->scopes)[$status];
        return $builder->where('scope', $value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function collectives()
    {
        return $this->belongsToMany(Collective::class,'collective_notic','notic_id','class_id');
    }
}
