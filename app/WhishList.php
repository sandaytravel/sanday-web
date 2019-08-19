<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class WhishList extends Model{
    protected $table = "wishlist";
    public function activity(){
        return $this->belongsTo('App\Activity','activity_id','id');
    }
    
}