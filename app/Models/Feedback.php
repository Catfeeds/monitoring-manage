<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tanmo\Search\Traits\Search;
use Illuminate\Database\Eloquent\SoftDeletes;
class Feedback extends Model
{
    //
    use Search;

    protected $table = "feedbacks";

    public function covers(){
        return $this->hasMany(FeedbackCover::class,'feedback_id','id');
    }
}
