<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $table = "transactions";

    public function orders() {
        return $this->hasMany('App\Orders', 'transaction_id', 'id');
    }
}
