<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    //
   // use SoftDeletes;
    protected $fillable = [
        'name', 'status', 'image',
    ];

    protected $table = 'category';

    protected $appends = [ 'totalItems','imagePath','completeImage'];
    public function getTotalItemsAttribute()
    {
        return Item::where('category_id',$this->attributes['id'])->get()->count();
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
