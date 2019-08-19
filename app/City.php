<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = "city";
    
    public function country() {
        return $this->belongsTo('App\Country', 'country_id', 'id');
    }
    public function categories(){
        return $this->hasMany('App\Citycategory', 'city_id', 'id');
    }
    public function activities(){
        return $this->hasMany('App\Activity', 'city_id', 'id');
    }
}
