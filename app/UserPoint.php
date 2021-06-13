<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPoint extends Model
{
    //
    protected $fillable = [
        'user_id', 'total_point', 'use_point', 'total_spent', 'shop_id',
    ];

    protected $table = 'user_point';
}
