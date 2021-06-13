<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    //
    // use SoftDeletes;
    protected $fillable = [
        'customer_id', 'order_id','shop_id', 'message','rate','deliveryBoy_id','item_id','package_id',
    ];
    protected $table = 'review';

    protected $appends = ['shopName','imagePath','customer','completeImage'];

    public function getShopNameAttribute()
    {
        $shop = Order::where('id',$this->attributes['order_id'])->first()->shop_id;
        return Shop::where('id',$shop)->first()->name;
    }

    public function getImagePathAttribute()
    {
        return url('images/upload') . '/';
    }

    public function getCustomerAttribute()
    {
        return User::findOrFail($this->attributes['customer_id']);
    }

    public function getCompleteImageAttribute()
    {
        return url('images/upload') . '/'.$this->attributes['image'];
    }
}
