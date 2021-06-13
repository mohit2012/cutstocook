<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    //
    // use SoftDeletes;
    protected $fillable = [
        'name', 'description', 'location', 'image', 'address', 'pincode', 'latitude', 'longitude', 'licence_code','phone','website','cancle_charge','radius',
        'rastaurant_charge', 'delivery_charge', 'delivery_time', 'featured', 'veg', 'status','user_id','open_time','close_time','exclusive','avarage_plate_price',
    ];

    protected $table = 'shop';

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function locationData()
    {
        return $this->hasOne('App\Location', 'id', 'location');
    }

    protected $appends = ['totalOrder','rate','totalReview','imagePath','itemNames','completeImage'];

    public function getTotalOrderAttribute()
    {
       return Order::where('shop_id',$this->attributes['id'])->get()->count();
    }


    public function getRateAttribute()
    {
        $review =  Review::where('shop_id', $this->attributes['id'])->get(['rate']);
        if(count($review)>0){
            $totalRate = 0;
            foreach ($review as $r) {
                $totalRate=$totalRate+$r->rate;
            }
            return round($totalRate / count($review), 1);
        }else{
            return 0;
        }
    }

    public function getTotalReviewAttribute()
    {
        $review =  Review::where('shop_id', $this->attributes['id'])->get(['rate']);
        if(count($review)>0){
            return count($review);
        }else{
            return 0;
        }
    }

    public function getImagePathAttribute()
    {
        return url('images/upload') . '/';
    }
    public function getItemNamesAttribute()
    {
        $name= array();
        $item =  Item::where('shop_id', $this->attributes['id'])->get();
        foreach ($item as $value) {
            array_push($name,$value->name);
        }
        return implode(',',$name);
    }

    public function getCompleteImageAttribute()
    {
        return url('images/upload') . '/'.$this->attributes['image'];
    }

}
