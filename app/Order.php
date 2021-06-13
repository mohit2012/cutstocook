<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    //
   // use SoftDeletes;
    protected $fillable = [
        'order_no', 'owner_id', 'location_id','shop_id','customer_id','deliveryBoy_id','payment','discount','payment_type','address_id',
        'order_status','payment_status','payment_token','review_status','shop_charge','delivery_charge','items','reject_by','delivery_type',
        'coupon_id','coupon_price','time','date','shopReview_status','driverReview_status','driver_otp','package_id','cancel_reason',
    ];

    protected $table = 'orders';
    
    public function location()
    {
        return $this->hasOne('App\Location', 'id', 'location_id');
    }

    public function shop()
    {
        return $this->hasOne('App\Shop', 'id', 'shop_id');
    }

    public function customer() 
    {
        return $this->hasOne('App\User', 'id', 'customer_id');
    }

    public function deliveryGuy()
    {
        return $this->hasOne('App\User', 'id', 'deliveryBoy_id');
    }
    public function orderItem()
    {
        return $this->hasMany('App\OrderChild', 'order_id', 'id');
    }

    protected $appends = ['orderItems','quantityTotal'];

    public function getOrderItemsAttribute()
    {
         return OrderChild::where('order_id',$this->attributes)->get();
       
    }
    public function getQuantityTotalAttribute()
    {
        return OrderChild::where('order_id',$this->attributes)->sum('quantity');               
    }


}
