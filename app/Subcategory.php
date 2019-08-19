<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $table = "subcategory";
    
    public function category(){
        return $this->belongsTo('App\Category','category_id','id');
    }
    public function activities(){
        return $this->hasMany('App\Activity','subcategory_id','id');
    }
}
