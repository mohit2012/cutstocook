<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    protected $fillable = [
        'user_id', 'order_id', 'title','message','image','driver_id','notification_type',
    ];

    protected $table = 'notification';

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
