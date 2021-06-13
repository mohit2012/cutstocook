<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Auth;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name', 'email', 'password','phone','dateOfBirth','role','image','verify','otp','location','address','address_id','referral_code','referral_user','phone_code','driver_radius',
        'provider','provider_token','favourite','device_token','fcm_token','cover_image','enable_notification','enable_location','enable_call','friend_code','free_order','lat','lang','driver_available',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['shops','imagePath','FullAddress','completeImage'];

    public function getShopsAttribute()
    {
        if(Auth::check())
        {
            if($this->attributes['role']==1)
            {
                return Shop::where('user_id',$this->attributes['id'])->get();
            }
            else{
                return [];
            }
        }
        else{
            if(Auth::guard('mainAdmin')->check()){
                return Shop::where('user_id',$this->attributes['id'])->get();
            }
            else{
                return [];
            }
        }

    }

    public function getImagePathAttribute()
    {
        return url('images/upload') . '/';
    }

    public function getFullAddressAttribute()
    {

        if(isset($this->attributes['address_id'])){
            if($this->attributes['address_id']!=null){
                return UserAddress::where('id',$this->attributes['address_id'])->first();
            }else{
                return null;
            }
        }
        else{
            return null;
        }
    }

    public function getCompleteImageAttribute()
    {
        return url('images/upload') . '/'.$this->attributes['image'];
    }
}
