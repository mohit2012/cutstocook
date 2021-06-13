<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroceryCategory extends Model
{
    //
    protected $fillable = [
        'name', 'status', 'image'
    ];

    protected $table = 'grocery_category';

    protected $appends = [ 'imagePath','completeImage'];

    public function getImagePathAttribute()
    {
        return url('images/upload') . '/';
    }

    public function getCompleteImageAttribute()
    {
        return url('images/upload') . '/'.$this->attributes['image'];
    }
}
