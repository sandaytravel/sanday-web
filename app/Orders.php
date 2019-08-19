<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    //
    protected $table = "orders";

    public function oredr_ietms() {
        return $this->hasMany('App\Orderitems', 'order_id', 'id');
    }
    public function activity() {
        return $this->belongsTo('App\Activity', 'activity_id', 'id');
    }
    public function transaction() {
        return $this->belongsTo('App\Transaction', 'transaction_id', 'id');
    }
    public function user() {
        return $this->belongsTo('App\User', 'customer_id', 'id');
    }
}
