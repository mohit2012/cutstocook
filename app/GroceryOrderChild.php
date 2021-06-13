<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroceryOrderChild extends Model
{
    //

    protected $fillable = [
        'order_id', 'item_id', 'price','quantity',
    ];
    protected $table = 'grocery_order_child';
    
    protected $appends = [ 'itemName' ,'itemImage','imagePath'];
    public function getItemNameAttribute()
    {
        if($this->attributes['item_id'] != null){
            return GroceryItem::where('id',$this->attributes['item_id'])->first()->name;
        }
        else{
            return null;
        }
    }
    public function getItemImageAttribute()
    {
        if($this->attributes['item_id'] != null){
            return GroceryItem::where('id',$this->attributes['item_id'])->first()->image;
        }
        else{
            return null;
        }
    }
    public function getImagePathAttribute()
    {
        return url('images/upload') . '/';
    }

    
}
