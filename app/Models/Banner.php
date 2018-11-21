<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    /**
     * @var array
     */
    protected $casts = [
        'redirect' => 'array'
    ];

    /**
     * @param $value
     * @return string
     */
    public function getCoverAttribute($value)
    {
        return Storage::disk('public')->url($value);
    }

    /**
     * @param $value
     * @return string
     */
    public function getLinkTypeAttribute($value)
    {
        switch ($value){
            case 0:
                return  'url';
            case 1:
                return  'article';
            case 2:
                return  'goods';
        }
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOpen(Builder $query)
    {
        return $query->where('status', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function articleTarget(){
        return $this->belongsTo(Press::class,'link','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function goodsTarget(){
        return $this->belongsTo(Item::class,'link','id');
    }
}
