<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Explore extends Model
{
    protected $table = "explore";
    
    public function images(){
        return $this->hasMany('App\Exploreimages','explore_id','id');
    }
}
