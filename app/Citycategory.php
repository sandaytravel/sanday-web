<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Citycategory extends Model {

    protected $table = "city_categories";

    public function city() {
        return $this->belongsTo('App\City', 'city_id', 'id');
    }
    public function category() {
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }

}
