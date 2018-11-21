<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class School extends Model
{
  protected $table = 'schools';

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function students()
  {
      return $this->hasMany(Student::class);
  }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
  public function parents()
  {
      return $this->belongsToMany(User::class,'school_parent','school_id','parent_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function collectives()
  {
      return $this->hasMany(Collective::class);
  }

    /**
     * @param $value
     * @return string
     */
    public function getAvatarAttribute($value)
    {
        return Storage::disk('public')->url($value);
    }

    public function covers(){
        return $this->hasMany(SchoolCover::class,'school_id','id');
    }

}
