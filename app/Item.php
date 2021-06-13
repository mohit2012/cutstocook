<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    //
    // use SoftDeletes;
    protected $fillable = [
        'name', 'description', 'image', 'price','category_id','shop_id','isNew','isPopular','isVeg','status',
    ];

    protected $table = 'item';


    public function category()
    {
        return $this->hasOne('App\Category', 'id', 'category_id');
    }

    public function shop()
    {
        return $this->hasOne('App\Shop', 'id', 'shop_id');
    }

    protected $appends = ['imagePath','rate','categoryName','totalReview','completeImage'];

    public function getImagePathAttribute()
    {
        return url('images/upload') . '/';
    }
    public function getCategoryNameAttribute()
    {
        return Category::where('id',$this->attributes['category_id'])->first()->name;
    }

    public function getRateAttribute()
    {
        $review =  Review::where('item_id', $this->attributes['id'])->get(['rate']);
        if(count($review)>0){
            $totalRate = 0;
            foreach ($review as $r) {
                $totalRate=$totalRate+$r->rate;
            }
            return round( $totalRate / count($review), 1);
        }else{
            return 0;
        }
    }
    public function getTotalReviewAttribute()
    {
        $review =  Review::where('item_id', $this->attributes['id'])->get(['rate']);
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
