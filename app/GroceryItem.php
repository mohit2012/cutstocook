<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroceryItem extends Model
{
    //
     protected $fillable = [
        'name', 'user_id','description', 'image', 'fake_price','sell_price','category_id','shop_id','subcategory_id','status',
        'brand','weight','stoke',
    ];

    protected $table = 'grocery_item';

    public function category()
    {
        return $this->hasOne('App\GroceryCategory', 'id', 'category_id');
    }

    public function subcategory()
    {
        return $this->hasOne('App\GrocerySubCategory', 'id', 'subcategory_id');
    }

    protected $appends = [ 'imagePath','shopName','completeImage'];

    public function getImagePathAttribute()
    {
        return url('images/upload') . '/';
    }
    public function getShopNameAttribute()
    {
        $g= GroceryShop::find($this->attributes['shop_id']);
        if($g){
        return $g->name;
        }
        return "no data";
        // return GroceryShop::find($this->attributes['shop_id'])->name;
    }

    public function getCompleteImageAttribute()
    {
        return url('images/upload') . '/'.$this->attributes['image'];
    }

}
