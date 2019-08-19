<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = "cart";
  
    public function activity(){
        return $this->belongsTo('App\Activity','activity_id','id');
    }
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }
    public function package(){
        return $this->belongsTo('App\ActivityPackageOptions','package_id','id');
    }
    public function package_quntity(){
        return $this->belongsTo('App\ActivitypackageQuantity','package_quantity_id','id');
    }
}