<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class OwnerSetting extends Model
{
    //
//    use SoftDeletes;
    protected $fillable = [
        'user_id', 'web_notification', 'status','play_sound','sound','coupon','default_food_order_status','default_grocery_order_status',
    ];
    protected $table = 'owner_setting';
    
}