<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    //
    // use SoftDeletes;
    protected $fillable = [
        'name', 'code', 'shop_id', 'description', 'type', 'discount', 'max_use', 'start_date', 'end_date','status','use_count','use_for','image',
    ];

    protected $table = 'coupon';

    protected $appends = [ 'shopName' ,'imagePath','completeImage'];

    public function getShopNameAttribute()
    {
        if($this->attributes['use_for'] == "Food")
        {
            return Shop::findOrFail($this->attributes['shop_id'])->name;
        }
        else if($this->attributes['use_for'] == "Grocery")
        {
            return GroceryShop::findOrFail($this->attributes['shop_id'])->name;
        }
    }

    public function getImagePathAttribute()
    {
        return url('images/upload') . '/';
    }

    public function getCompleteImageAttribute()
    {
        return url('images/upload') . '/'.$this->attributes['image'];
    }
}
