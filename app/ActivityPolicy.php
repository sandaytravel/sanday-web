<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityPolicy extends Model
{
    protected $table = "activity_policies";
    
    public function activity(){
        return $this->belongsTo('App\Activity','activity_id','id');
    }
    public function general_policy(){
        return $this->belongsTo('App\Generalpolicy','policy_id','id');
    }
}
