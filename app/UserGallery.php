<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGallery extends Model
{
    //
    protected $fillable = [
        'user_id', 'image',

    ];

    protected $table = 'user_gallery';

    protected $appends = ['imagePath','completeImage'];

    public function getImagePathAttribute()
    {
        return url('images/upload') . '/';
    }

    public function getCompleteImageAttribute()
    {
        return url('images/upload') . '/'.$this->attributes['image'];
    }
}
