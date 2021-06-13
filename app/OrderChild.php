<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderChild extends Model
{
    //
    protected $fillable = [
        'order_id', 'item', 'price','quantity','package_id',
    ];
    protected $table = 'order_child';
    
    protected $appends = [ 'itemName','packageName','itemImage','packageImage','imagePath'];
    public function getItemNameAttribute()
    {
        if($this->attributes['item'] != null){
            return Item::where('id',$this->attributes['item'])->first()->name;
        }
        else{
            return null;
        }        
    }
    public function getPackageNameAttribute()
    {
        if($this->attributes['package_id'] != null){
            return Package::where('id',$this->attributes['package_id'])->first()->name;
        }
        else{
            return null;
        }        
    }
    public function getItemImageAttribute()
    {
        if($this->attributes['item'] != null){
            return Item::where('id',$this->attributes['item'])->first()->image;
        }
        else{
            return null;
        }
        
    }
    public function getPackageImageAttribute()
    {
        if($this->attributes['package_id'] != null){
            return Package::where('id',$this->attributes['package_id'])->first()->image;
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
