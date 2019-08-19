<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityReview extends Model
{
    protected $table = "activity_reviews";
    
    public function activity(){
        return $this->belongsTo('App\Activity','activity_id','id');
    }
    public function images(){
        return $this->hasMany('App\Reviewimages','activity_reviews_id','id');
    }
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }
}