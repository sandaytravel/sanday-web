<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orderitems extends Model
{
    //
    protected $table = "orderitems";

    public function orders() {
        return $this->hasMany('App\Orders', 'order_id', 'id');
    }
    public function activitypackageoptions(){
        return $this->belongsTo('App\ActivityPackageOptions', 'package_id', 'id');
    }
    public function packagequantity(){
        return $this->belongsTo('App\ActivitypackageQuantity','package_quantity_id','id');
    }
}