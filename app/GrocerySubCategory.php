<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrocerySubCategory extends Model
{
    //
    protected $fillable = [
        'name','category_id','owner_id', 'shop_id' ,'status', 'image','completeImage'
    ];

    protected $table = 'grocery_sub_category';

    protected $appends = [ 'imagePath','categoryName','shopName'];

    public function getImagePathAttribute()
    {
        return url('images/upload') . '/';
    }

    public function getCategoryNameAttribute()
    {
        $data =  GroceryCategory::find($this->attributes['category_id']);
        if($data){
            return $data->name;
        }
        else{
            return null;
        }
    }
    public function getShopNameAttribute()
    {
        return GroceryShop::find($this->attributes['shop_id'])->name;
    }

    public function getCompleteImageAttribute()
    {
        return url('images/upload') . '/'.$this->attributes['image'];
    }
}
