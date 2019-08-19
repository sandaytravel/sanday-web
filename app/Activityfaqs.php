<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activityfaqs extends Model
{
    protected $table = "activity_faqs";
    
    public function activity(){
        return $this->belongsTo('App\Activity','activity_id','id');
    }
}
