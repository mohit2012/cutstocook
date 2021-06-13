<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    //
    use Notifiable,SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'remember_token', 'role','image','fcm_token'
    ];

    protected $table = 'admin';
}
