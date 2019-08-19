<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = "activity";
    
    /*
     * Activity Relations
     */
    public function packageoptions(){
        return $this->hasMany('App\ActivityPackageOptions','activity_id','id');
    }
    
    public function policy(){
        return $this->hasMany('App\ActivityPolicy','activity_id','id');
    }
    
    public function reviews(){
        return $this->hasMany('App\ActivityReview','activity_id','id');
    }
    
    public function faqs(){
        return $this->hasMany('App\Activityfaqs','activity_id','id');
    }
    
    public function merchant(){
        return $this->belongsTo('App\User','merchant_id','id');
    }
    
    public function category(){
        return $this->belongsTo('App\Category','category_id','id');
    }
    
    public function subcategory(){
        return $this->belongsTo('App\Subcategory','subcategory_id','id');
    }

    public function city(){
        return $this->belongsTo('App\City', 'city_id', 'id');
    }

    public function activitypackageoptions(){
        return $this->hasMany('App\ActivityPackageOptions', 'activity_id', 'id');
    }
    public function packagequantity(){
        return $this->hasMany('App\ActivitypackageQuantity','activity_package_id','id');
    }
    public function whishlist(){
        return $this->belongsTo('App\WhishList','id','activity_id');
    }
    /*
     * End Activity Relations
     */
}
