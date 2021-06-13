<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'currency', 'map_key', 'push_notification' ,'onesignal_api_key','onesignal_auth_key','onesignal_app_id','onesignal_project_number',
        'twilio_phone_number','twilio_account_id','twilio_auth_token', 'sms_twilio',
        'mail_notification','mail_host','mail_port','mail_username','mail_password','sender_email',
        'delivery_charge_amount','delivery_charge_per','commission_amount','commission_per',
        'user_verify','phone_verify','email_verify','request_duration','sell_product','default_driver_radius',
        'web_notification','web_onesignal_app_id','web_onesignal_api_key','web_onesignal_auth_key','banner_image',
        'license_key','license_name','license_status','primary_color','terms_condition','privacy_policy','about_us',
        'fcm_server_key','fcm_public_key','apiKey','authDomain','projectId','storageBucket','messagingSenderId','appId','measurementId'
    ];
    protected $table = 'general_setting';
}
