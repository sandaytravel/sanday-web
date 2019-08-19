<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Continent extends Model
{
    protected $table = "continents";
    
    public function contries(){
        return $this->hasMany('App\Country', 'continent_id', 'id');
    }
}
