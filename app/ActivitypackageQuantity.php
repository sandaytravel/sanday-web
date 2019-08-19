<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivitypackageQuantity extends Model
{
    protected $table = "activity_package_quantity";
    public function cart(){
        return $this->hasOne('App\Cart','package_quantity_id','id');
    }

}
