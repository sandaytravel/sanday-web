<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    protected $table = "permissions";

    public function module() {
        return $this->belongsTo('App\Modules', 'module_id', 'id');
    }
}
