<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PointLog extends Model
{
    //
    protected $fillable = [
        'user_id', 'order_id', 'shop_id', 'point', 'redeem_point',
    ];

    protected $table = 'point_log';
}
