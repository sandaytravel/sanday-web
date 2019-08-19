<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityPackageOptions extends Model
{
    protected $table = "activity_package_options";
    
    public function activity(){
        return $this->belongsTo('App\Activity','activity_id','id');
    }
    public function packagequantity(){
        return $this->hasMany('App\ActivitypackageQuantity','activity_package_id','id');
    }
}
