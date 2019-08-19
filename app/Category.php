<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "categories";
    
    public function subcategories(){
        return $this->hasMany('App\Subcategory','category_id','id');
    }

    public function activities(){
        return $this->hasMany('App\Activity','category_id','id');
    }
}
