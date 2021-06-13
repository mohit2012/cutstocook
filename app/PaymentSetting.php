<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentSetting extends Model
{
    //
    //use SoftDeletes;
    protected $fillable = [
        'cod', 'stripe', 'paypal','razor','stripePublicKey', 'stripeSecretKey',
        'paypalSendbox', 'paypalProduction','razorPublishKey','razorSecretKey',
        'paytab_secret_key','paytab_email','paytabs','flutterwave_public_key','paystack_public_key','paystack','flutterwave'
    ];
    protected $table = 'payment_setting';

}
