<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /*-
    *Role id by acess
    *
    */
    public function hasAccess($user, $ability, $readWrite) {
        $permisson = Permissions::with('module')->whereHas('module', function ($query) use($ability) {
                    $query->where('name', $ability);
                })->where('role_id', $user->role_id)->first();
        if ($permisson) {
            if ($readWrite[0] == 'read' && $permisson->p_read == 1) {
                return true;
            } else if ($readWrite[0] == 'write' && $permisson->p_write == 1) {
                return true;
            } else if ($readWrite[0] == 'sidebar' && $permisson->p_sidebar == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}
