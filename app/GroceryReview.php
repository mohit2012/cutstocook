<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroceryReview extends Model
{
    //
    protected $fillable = [
        'customer_id', 'order_id','shop_id', 'message','rate','deliveryBoy_id','item_id',
    ];
    protected $table = 'grocery_review';

    protected $appends = ['imagePath','customer','completeImage'];

    public function getImagePathAttribute()
    {
        return url('images/upload') . '/';
    }

    public function getCustomerAttribute()
    {
        return User::find($this->attributes['customer_id']);
    }

    public function getCompleteImageAttribute()
    {
        return url('images/upload') . '/'.$this->attributes['image'];
    }

}
