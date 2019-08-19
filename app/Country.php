<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = "country";
    
    public function continent() {
        return $this->belongsTo('App\Continent', 'continent_id', 'id');
    }
    
    public function city(){
        return $this->hasMany('App\City', 'country_id', 'id');
    }
    
    public function activities(){
        return $this->hasMany('App\Activity', 'city_id', 'id');
    }
}
