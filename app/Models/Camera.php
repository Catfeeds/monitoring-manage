<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tanmo\Search\Traits\Search;
class Camera extends Model
{
    use Search;
  protected $table = 'cameras';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function collective()
    {
        return $this->belongsTo(Collective::class,'class_id','id');
    }
}
