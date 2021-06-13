<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Package extends Model
{
    //

    protected $fillable = [
        'name', 'shop_id', 'owner_id','items','total_price','package_price','status','image'
    ];
    protected $table = 'package';

    protected $appends = ['shopName','imagePath','itemsName','rate','totalReview','completeImage'];

    public function getShopNameAttribute()
    {
        return Shop::where('id',$this->attributes['shop_id'])->first()->name;
    }

    public function getItemsNameAttribute()
    {
        $name = array();
        $item = Item::whereIn('id',explode(',',$this->attributes['items']))->get();
        foreach ($item as $value) {
            array_push($name,$value->name);
        }
        return implode(',',$name);
    }


    public function getImagePathAttribute()
    {
        return url('images/upload') . '/';
    }

    public function getRateAttribute()
    {
        $review =  Review::where('package_id', $this->attributes['id'])->get(['rate']);
        if(count($review)>0){
            $totalRate = 0;
            foreach ($review as $r) {
                $totalRate=$totalRate+$r->rate;
            }
            return  $totalRate / count($review);
        }else{
            return 0;
        }
    }

    public function getTotalReviewAttribute()
    {
        $review =  Review::where('package_id', $this->attributes['id'])->get(['rate']);
        if(count($review)>0){
            return count($review);
        }else{
            return 0;
        }
    }

    public function getCompleteImageAttribute()
    {
        return url('images/upload') . '/'.$this->attributes['image'];
    }
}
