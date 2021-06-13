@extends('admin.master', ['title' => __('Settings')])

@section('content_setting')
    @include('admin.layout.topHeader', [
        'title' => __('Settings') ,
        'class' => 'col-lg-7'
    ])
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card bg-secondary form-card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Settings') }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <?php $status = \App\Setting::find(1)->license_status;  ?>
                                <ul class="nav nav-tabs tabs-left sideways">
                                    <li><a href="#general-v" class="{{ $status==1?'active':'event-none'}} " data-toggle="tab"> <i class="ni ni-settings-gear-65 mr-2"></i>{{ __("General") }}</a></li>
                                    <li><a href="#payment-v" class="{{ $status==0?'event-none':''}}" data-toggle="tab"><i class="far fa-credit-card mr-2"></i>{{ __("Payment Gateway") }}</a></li>
                                    <li><a href="#language-v" class="{{ $status==0?'event-none':''}}" data-toggle="tab"><i class="fas fa-language mr-2"></i>{{ __("Language Setting") }}</a></li>
                                    <li><a href="#verification-v" class="{{ $status==0?'event-none':''}}" data-toggle="tab"><i class="fas fa-user-check mr-2"></i>{{ __("User Verification") }}</a></li>
                                    <li><a href="#commission-v" class="{{ $status==0?'event-none':''}}" data-toggle="tab"><i class="fas fa-donate mr-2"></i>{{ __("Commission Setting") }} </a></li>
                                    <li><a href="#notification-v" class="{{ $status==0?'event-none':''}}" data-toggle="tab"><i class="far fa-bell mr-2"></i>{{ __("Push Notification") }}</a></li>
                                    <li><a href="#web-notification-v" class="{{ $status==0?'event-none':''}}" data-toggle="tab"><i class="fas fa-bell mr-2"></i>{{ __("Web Notification") }}</a></li>
                                    <li><a href="#mail-v" class="{{ $status==0?'event-none':''}}" data-toggle="tab"><i class="far fa-envelope mr-2"></i>{{ __("Mail") }}</a></li>
                                    <li><a href="#sms-v" class="{{ $status==0?'event-none':''}}" data-toggle="tab"><i class="far fa-comments mr-2"></i>{{ __("SMS Gateway") }}</a></li>
                                    <li><a href="#map-v" class="{{ $status==0?'event-none':''}}" data-toggle="tab"><i class="far fa-map  mr-2"></i>{{ __("Map Setting") }}</a></li>
                                    <li><a href="#website-v" class="{{ $status==0?'event-none':''}}" data-toggle="tab"><i class="far fa-file-alt mr-2"></i>{{ __("Website Content") }}</a></li>
                                    <li><a href="#additional-v" class="{{ $status==0?'event-none':''}}" data-toggle="tab"><i class="ni ni-settings mr-2"></i>{{ __("Additional setting") }}</a></li>
                                    <li><a href="#license-v" class="{{ $status==0?'active':''}}" data-toggle="tab"><i class="ni ni-settings mr-2"></i>{{ __("License setting") }}</a></li>
                                </ul>
                            </div>
                            <div class="col-9">
                                <div class="tab-content">
                                    <div class="tab-pane {{ $status==1?'active':''}}" id="general-v">
                                        <form method="post" id="company-setting-form" action="{{url('mainAdmin/adminSetting/'.$companyData->id)}}" autocomplete="off" enctype="multipart/form-data" >
                                            @csrf
                                            @method('put')
                                            <h6 class="heading-small text-muted mb-4">{{ __("Company's General Setting") }}</h6>
                                            <div>
                                                <div class="form-group row{{ $errors->has('name') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ $companyData->name }}" required >
                                                        @if ($errors->has('name'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('name') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row{{ $errors->has('address') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-address">{{ __('Address') }}</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="address" id="input-address" class="form-control form-control-alternative{{ $errors->has('address') ? ' is-invalid' : '' }}" placeholder="{{ __('Address') }}" value="{{ $companyData->address }}"  required>
                                                        @if ($errors->has('address'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('address') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row{{ $errors->has('phone') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-phone">{{ __('Phone') }}</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="phone" id="input-phone" class="form-control form-control-alternative{{ $errors->has('phone') ? ' is-invalid' : '' }}" placeholder="{{ __('Phone') }}" value="{{ $companyData->phone }}" required >
                                                        @if ($errors->has('phone'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('phone') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group row{{ $errors->has('email') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-email">{{ __('Email') }}</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="email" name="email" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email ID') }}" value="{{ $companyData->email }}" required >
                                                        @if ($errors->has('email'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('email') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row{{ $errors->has('website') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-website">{{ __('Website') }}</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="website" id="input-website" class="form-control form-control-alternative{{ $errors->has('website') ? ' is-invalid' : '' }}" placeholder="{{ __('Website') }}" value="{{ $companyData->website }}"  >
                                                        @if ($errors->has('website'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('website') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-3"></div>
                                                    <div class="col-9">
                                                        <img src="{{url('images/upload/'.$companyData->logo)}}" id="setting-logo" style="width:160px;height:60px;margin-bottom:15px;border-radius:5px;">
                                                    </div>
                                                </div>

                                                <div class="form-group row{{ $errors->has('logo') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-logo">{{ __('Logo') }}</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="logo" id="logo" >
                                                            <label class="custom-file-label" for="logo">Select file</label>
                                                        </div>
                                                        @if ($errors->has('imaglogoe'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('logo') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-3"></div>
                                                    <div class="col-9">
                                                        <img src="{{url('images/upload/'.$companyData->favicon)}}" id="setting-favicon" style="width:90px;height:90px;margin-bottom:15px;border-radius:5px;">
                                                    </div>
                                                </div>
                                                <div class="form-group row{{ $errors->has('favicon') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-favicon">{{ __('Favicon Icon') }}</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="favicon" id="favicon" >
                                                            <label class="custom-file-label" for="favicon">Select file</label>
                                                        </div>
                                                        @if ($errors->has('favicon'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('favicon') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row {{ $errors->has('description') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-description">{{ __('Description') }}</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <textarea name="description" id="input-description" class="form-control form-control-alternative{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" >{{$companyData->description}}</textarea>
                                                        @if ($errors->has('description'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('description') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>



                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-primary mt-4">{{ __('Save') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane" id="payment-v">
                                        <form method="post" action="{{url('mainAdmin/savePaymentSetting')}}" autocomplete="off" enctype="multipart/form-data" >
                                            @csrf

                                            <h6 class="heading-small text-muted mb-4">{{ __("Payment Gateway Setting") }}</h6>
                                            <div>
                                                <div class="form-group{{ $errors->has('cod') ? ' has-danger' : '' }}">
                                                    <div class="row">
                                                        <div class="col-4"> <label class="form-control-label">{{ __('COD (Cash on delivery Payment)') }}</label></div>
                                                        <div class="col-8">
                                                            <label class="custom-toggle">
                                                                <input type="checkbox" value="1" {{$paymentData->cod == 1?'checked':''}} name="cod" id="cod">
                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="form-group{{ $errors->has('stripe') ? ' has-danger' : '' }}">
                                                    <div class="row">
                                                        <div class="col-4"> <label class="form-control-label">{{ __('Stripe (Online Payment with Stripe)') }}</label></div>
                                                        <div class="col-8">
                                                            <label class="custom-toggle">
                                                                <input type="checkbox" value="1" {{$paymentData->stripe == 1?'checked':''}} name="stripe" id="stripe">
                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                <div class="form-group{{ $errors->has('paypal') ? ' has-danger' : '' }}">
                                                    <div class="row " >
                                                        <div class="col-4"> <label class="form-control-label">{{ __('Paypal (Paypal Express Checkout)') }}</label></div>
                                                        <div class="col-8">
                                                            <label class="custom-toggle">
                                                                <input type="checkbox" value="1" {{$paymentData->paypal == 1?'checked':''}} name="paypal" id="paypal">
                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group{{ $errors->has('paytabs') ? ' has-danger' : '' }}">
                                                    <div class="row">
                                                        <div class="col-4"> <label
                                                                class="form-control-label">{{ __('Paytabs (Online Payment with Paytabs)') }}</label></div>
                                                        <div class="col-8">
                                                            <label class="custom-toggle">
                                                                <input type="checkbox" value="1" {{$paymentData->paytabs == 1?'checked':''}} name="paytabs"
                                                                    id="paytabs">
                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No"
                                                                    data-label-on="Yes"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group{{ $errors->has('razor') ? ' has-danger' : '' }}">
                                                    <div class="row pb-1">
                                                        <div class="col-4"> <label class="form-control-label">{{ __('RazorPay') }}</label></div>
                                                        <div class="col-8">
                                                            <label class="custom-toggle">
                                                                <input type="checkbox" value="1" {{$paymentData->razor == 1?'checked':''}} name="razor" id="razor">
                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group{{ $errors->has('flutterwave') ? ' has-danger' : '' }}">
                                                    <div class="row pb-1">
                                                        <div class="col-4"> <label class="form-control-label">{{ __('Flutterwave') }}</label></div>
                                                        <div class="col-8">
                                                            <label class="custom-toggle">
                                                                <input type="checkbox" value="1" {{$paymentData->flutterwave == 1?'checked':''}} name="flutterwave" id="flutterwave">
                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group{{ $errors->has('paystack') ? ' has-danger' : '' }}">
                                                    <div class="row pb-1" style="border-bottom:1px solid #ddd;">
                                                        <div class="col-4"> <label class="form-control-label">{{ __('Paystack') }}</label></div>
                                                        <div class="col-8">
                                                            <label class="custom-toggle">
                                                                <input type="checkbox" value="1" {{$paymentData->paystack == 1?'checked':''}} name="paystack" id="paystack">
                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- <h6 class="heading-small text-muted mb-4">{{ __("Stripe") }}</h6>
                                                <div class="form-group row{{ $errors->has('stripePublicKey') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-stripePublicKey">{{ __('Stripe Public Key') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="stripePublicKey" id="input-stripePublicKey" class="form-control form-control-alternative{{ $errors->has('stripePublicKey') ? ' is-invalid' : '' }}" placeholder="{{ __('Stripe Public Key') }}" value="{{ $paymentData->stripePublicKey }}" >
                                                        @if ($errors->has('stripePublicKey'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('stripePublicKey') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group row {{ $errors->has('stripeSecretKey') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-stripeSecretKey">{{ __('Stripe Secret Key') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="stripeSecretKey" id="input-stripeSecretKey" class="form-control form-control-alternative{{ $errors->has('stripeSecretKey') ? ' is-invalid' : '' }}" placeholder="{{ __('Stripe Secret Key:') }}" value="{{ $paymentData->stripeSecretKey }}" >
                                                        @if ($errors->has('stripeSecretKey'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('stripeSecretKey') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div> --}}
                                                <h6 class="heading-small text-muted mb-4 pt-4" >{{ __("RazorPay") }}</h6>
                                                <div class="form-group row{{ $errors->has('razorPublishKey') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-razorPublishKey">{{ __('Razor Public Key') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="razorPublishKey" id="input-razorPublishKey" class="form-control form-control-alternative{{ $errors->has('razorPublishKey') ? ' is-invalid' : '' }}" placeholder="{{ __('RazorPay Public Key') }}" value="{{ $paymentData->razorPublishKey }}" >
                                                        @if ($errors->has('razorPublishKey'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('razorPublishKey') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group row {{ $errors->has('razorSecretKey') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-razorSecretKey">{{ __('RazorPay Secret Key') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="razorSecretKey" id="input-razorSecretKey" class="form-control form-control-alternative{{ $errors->has('razorSecretKey') ? ' is-invalid' : '' }}" placeholder="{{ __('RazorPay Secret Key:') }}" value="{{ $paymentData->razorSecretKey }}" >
                                                        @if ($errors->has('razorSecretKey'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('razorSecretKey') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <h6 class="heading-small text-muted mb-4 pt-4" style="border-top:1px solid #ddd;">{{ __("Paypal") }}</h6>
                                                <div class="form-group row {{ $errors->has('paypalSendbox') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-paypalSendbox">{{ __('Paypal Sandbox Key') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="paypalSendbox" id="input-paypalSendbox" class="form-control form-control-alternative{{ $errors->has('paypalSendbox') ? ' is-invalid' : '' }}" placeholder="{{ __('Paypal Sandbox Key:') }}" value="{{ $paymentData->paypalSendbox }}" >
                                                        @if ($errors->has('paypalSendbox'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('paypalSendbox') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group row {{ $errors->has('paypalProduction') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-paypalProduction">{{ __('Paypal Production Key') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="paypalProduction" id="input-paypalProduction" class="form-control form-control-alternative{{ $errors->has('paypalProduction') ? ' is-invalid' : '' }}" placeholder="{{ __('Paypal Production Key:') }}" value="{{ $paymentData->paypalProduction }}" >
                                                        @if ($errors->has('paypalProduction'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('paypalProduction') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>


                                                <h6 class="heading-small text-muted mb-4 pt-4" style="border-top:1px solid #ddd;">{{ __("Paytabs") }}</h6>
                                                <div class="form-group row{{ $errors->has('paytab_email') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-paytab_email">{{ __('Paytabs Merchant Email') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="email" name="paytab_email" id="input-paytab_email"
                                                            class="form-control form-control-alternative{{ $errors->has('paytab_email') ? ' is-invalid' : '' }}"
                                                            placeholder="{{ __('Paytabs Merchant Email') }}" value="{{ $paymentData->paytab_email }}">
                                                        @if ($errors->has('paytab_email'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('paytab_email') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group row {{ $errors->has('paytab_secret_key') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-paytab_secret_key">{{ __('Paytabs Secret Key') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="paytab_secret_key" id="input-paytab_secret_key"
                                                            class="form-control form-control-alternative{{ $errors->has('paytab_secret_key') ? ' is-invalid' : '' }}"
                                                            placeholder="{{ __('Paytabs Secret Key:') }}" value="{{ $paymentData->paytab_secret_key }}">
                                                        @if ($errors->has('paytab_secret_key'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('paytab_secret_key') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <h6 class="heading-small text-muted mb-4 pt-4" style="border-top:1px solid #ddd;">{{ __("Paystack") }}</h6>
                                                <div class="form-group row{{ $errors->has('paystack_public_key') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-paystack_public">{{ __('Paystack public key') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="paystack_public_key" id="input-paystack_public"
                                                            class="form-control form-control-alternative{{ $errors->has('Paystack_public_key') ? ' is-invalid' : '' }}"
                                                            placeholder="{{ __('Paystack publick key') }}" value="{{ $paymentData->paystack_public_key }}">
                                                        @if ($errors->has('paystack_public_key'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('paystack_public_key') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <h6 class="heading-small text-muted mb-4 pt-4" style="border-top:1px solid #ddd;">{{ __("Flutterwave") }}</h6>
                                                <div class="form-group row{{ $errors->has('flutterwave_public_key') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-paytab_email">{{ __('flutterwave public key') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="flutterwave_public_key" id="input-paytab_email" class="form-control form-control-alternative{{ $errors->has('Flutterwave_public_key') ? ' is-invalid' : '' }}" placeholder="{{ __('Flutterwave public key') }}" value="{{ $paymentData->flutterwave_public_key }}">
                                                        @if ($errors->has('flutterwave_public_key'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('flutterwave_public_key') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-primary mt-4">{{ __('Save') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane" id="language-v">
                                        <h6 class="heading-small text-muted mb-4">{{ __("Language Setting") }}</h6>
                                        <div class="row">
                                            <div class="col-3 mb-2 heading-small">{{ __("Language Name") }}</div>
                                            <div class="col-9 mb-2 heading-small">{{ __("Status") }}</div>
                                        </div>
                                        <div class="row languages mb-4" style="border-bottom: 1px solid #ddd;">
                                            @foreach ($language as $item)
                                            <div class="col-3 mb-2 text-muted"><span>{{$item->name}}</span></div>
                                            <div class="col-9 mb-2 text-muted">
                                                    <label class="custom-toggle">
                                                        <input type="checkbox" value="1" class="language-status" {{$item->status == 1?'checked':''}} name="status-{{$item->id}}" id="status-{{$item->id}}">
                                                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                    </label>
                                            </div>
                                            @endforeach
                                        </div>
                                        <form method="post" action="{{url('mainAdmin/Language')}}" autocomplete="off" enctype="multipart/form-data" files="true" >
                                            @csrf
                                            <h6 class="heading-small text-muted mb-4">{{ __("Add New Language") }}</h6>
                                            <div>
                                                <div class="form-group row {{ $errors->has('lang_name') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-lang_name">{{ __('Language Name') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" required name="lang_name" id="input-lang_name" class="form-control form-control-alternative{{ $errors->has('lang_name') ? ' is-invalid' : '' }}" placeholder="{{ __('Language Name') }}" >
                                                        @if ($errors->has('lang_name'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('lang_name') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row {{ $errors->has('file') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-file">{{ __('Language File') }}:<br>(only .json file)</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="custom-file">
                                                            <input type="file" required accept="application/JSON" class="custom-file-input form-control-alternative{{ $errors->has('file') ? ' is-invalid' : '' }}" name="file" id="file" >
                                                            @if ($errors->has('file'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('file') }}</strong>
                                                            </span>
                                                            @endif
                                                            <label class="custom-file-label" for="file">Select file</label>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row {{ $errors->has('icon') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-icon">{{ __('Language Map icon:') }}</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="custom-file">
                                                            <input type="file" required class="custom-file-input form-control-alternative{{ $errors->has('icon') ? ' is-invalid' : '' }}" name="icon" id="icon" >
                                                            @if ($errors->has('icon'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('icon') }}</strong>
                                                            </span>
                                                            @endif
                                                            <label class="custom-file-label" for="icon">Select file</label>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-primary mt-4">{{ __('Save') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane" id="verification-v">
                                        <form method="post" action="{{url('mainAdmin/saveVerificationSettings')}}" autocomplete="off" enctype="multipart/form-data" >
                                            @csrf
                                            <h6 class="heading-small text-muted mb-4">{{ __("User Verification Setting") }}</h6>
                                            <div>
                                                <div class="form-group{{ $errors->has('user_verify') ? ' has-danger' : '' }}">
                                                    <div class="row">
                                                        <div class="col-4"> <label class="form-control-label">{{ __('Verify User') }}:</label></div>
                                                        <div class="col-8">
                                                            <label class="custom-toggle">
                                                                <input type="checkbox" value="1" {{$setting->user_verify == 1?'checked':''}} name="user_verify" id="user_verify">
                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group{{ $errors->has('phone_verify') ? ' has-danger' : '' }}">
                                                    <div class="row">
                                                        <div class="col-4"> <label class="form-control-label">{{ __('Verification using Phone') }}:</label></div>
                                                        <div class="col-8">
                                                            <label class="custom-toggle">
                                                                <input type="checkbox" value="1" {{$setting->phone_verify == 1?'checked':''}} name="phone_verify" id="phone_verify">
                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group{{ $errors->has('email_verify') ? ' has-danger' : '' }}">
                                                    <div class="row">
                                                        <div class="col-4"> <label class="form-control-label">{{ __('Verification using Email') }}:</label></div>
                                                        <div class="col-8">
                                                            <label class="custom-toggle">
                                                                <input type="checkbox" value="1" {{$setting->email_verify == 1?'checked':''}} name="email_verify" id="email_verify">
                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-primary mt-4">{{ __('Save') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane" id="commission-v">
                                            <form method="post" action="{{url('mainAdmin/saveCommissionSettings')}}" autocomplete="off" enctype="multipart/form-data" >
                                                @csrf
                                                <h6 class="heading-small text-muted mb-4">{{ __("Commission Setting") }}</h6>
                                                <div>
                                                    <div class="form-group row {{ $errors->has('commission_per') ? ' has-danger' : '' }}">
                                                        <div class="col-3">
                                                            <label class="form-control-label" for="input-commission_per">{{ __('Foodeli Commission') }} <br>{{ __('(in Percentage)') }}:</label>
                                                        </div>
                                                        <div class="col-9">
                                                            <input type="number" name="commission_per" min="0" id="input-commission_per" class="form-control form-control-alternative{{ $errors->has('commission_per') ? ' is-invalid' : '' }}" placeholder="{{ __('Foodeli commission in percentage') }}" value="{{ $setting->commission_per }}" >
                                                            @if ($errors->has('commission_per'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('commission_per') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="form-group row {{ $errors->has('delivery_charge_per') ? ' has-danger' : '' }}">
                                                        <div class="col-3">
                                                            <label class="form-control-label" for="input-delivery_charge_per">{{ __('Delivery charge per order') }}<br>{{ __('(in Percentage)') }}:</label>
                                                        </div>
                                                        <div class="col-9">
                                                            <input type="number" name="delivery_charge_per" min="0" id="input-delivery_charge_per" class="form-control form-control-alternative{{ $errors->has('delivery_charge_per') ? ' is-invalid' : '' }}" placeholder="{{ __('Delivery charge per order(in Percentage)') }}" value="{{ $setting->delivery_charge_per }}" >
                                                            @if ($errors->has('delivery_charge_per'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('delivery_charge_per') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <button type="submit" class="btn btn-primary mt-4">{{ __('Save') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                    </div>


                                    <div class="tab-pane" id="notification-v">
                                        <form method="post" action="{{url('mainAdmin/saveNotificationSettings')}}" autocomplete="off" enctype="multipart/form-data" >
                                            @csrf

                                            <h6 class="heading-small text-muted mb-4">{{ __("Push Notification Setting") }}</h6>
                                            <div>
                                                <div class="form-group{{ $errors->has('push_notification') ? ' has-danger' : '' }}">
                                                    <div class="row">
                                                        <div class="col-3"> <label class="form-control-label">{{ __('Enable Push Notification') }}</label></div>
                                                        <div class="col-9">
                                                            <label class="custom-toggle">
                                                                <input type="checkbox" value="1" {{$setting->push_notification == 1?'checked':''}} name="push_notification" id="push_notification">
                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group row {{ $errors->has('onesignal_api_key') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-onesignal_api_key">{{ __('One Signal Rest Api Key') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="onesignal_api_key" id="input-onesignal_api_key" class="form-control form-control-alternative{{ $errors->has('onesignal_api_key') ? ' is-invalid' : '' }}" placeholder="{{ __('One Signal Rest Api Key') }}" value="{{ $setting->onesignal_api_key }}" >
                                                        @if ($errors->has('onesignal_api_key'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('onesignal_api_key') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group row {{ $errors->has('onesignal_auth_key') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-onesignal_auth_key">{{ __('One Signal Auth Key') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="onesignal_auth_key" id="input-onesignal_auth_key" class="form-control form-control-alternative{{ $errors->has('onesignal_auth_key') ? ' is-invalid' : '' }}" placeholder="{{ __('One Signal Auth Key') }}" value="{{ $setting->onesignal_auth_key }}" >
                                                        @if ($errors->has('onesignal_auth_key'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('onesignal_auth_key') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>


                                                <div class="form-group row {{ $errors->has('onesignal_app_id') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-onesignal_app_id">{{ __('One Signal AppID') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="onesignal_app_id" id="input-onesignal_app_id" class="form-control form-control-alternative{{ $errors->has('onesignal_app_id') ? ' is-invalid' : '' }}" placeholder="{{ __('One Signal AppID') }}" value="{{ $setting->onesignal_app_id }}" >
                                                        @if ($errors->has('onesignal_app_id'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('onesignal_app_id') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row {{ $errors->has('onesignal_project_number') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-onesignal_project_number">{{ __('One Signal Project No.') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="onesignal_project_number" id="input-onesignal_project_number" class="form-control form-control-alternative{{ $errors->has('onesignal_project_number') ? ' is-invalid' : '' }}" placeholder="{{ __('One Signal Project No.') }}" value="{{ $setting->onesignal_project_number }}" >
                                                        @if ($errors->has('onesignal_project_number'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('onesignal_project_number') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>



                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-primary mt-4">{{ __('Save') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane" id="web-notification-v">
                                        <form method="post" action="{{url('mainAdmin/saveWebNotificationSettings')}}" autocomplete="off" enctype="multipart/form-data" >
                                            @csrf

                                            <h6 class="heading-small text-muted mb-4">{{ __("Web Notification Setting") }}</h6>
                                            <div>
                                                <div class="form-group{{ $errors->has('web_notification') ? ' has-danger' : '' }}">
                                                    <div class="row">
                                                        <div class="col-3"> <label class="form-control-label">{{ __('Enable Web Notification') }}</label></div>
                                                        <div class="col-9">
                                                            <label class="custom-toggle">
                                                                <input type="checkbox" value="1" {{$setting->web_notification == 1?'checked':''}} name="web_notification" id="web_notification">
                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group row {{ $errors->has('web_onesignal_api_key') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-web_onesignal_api_key">{{ __('One Signal Rest Api Key') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="web_onesignal_api_key" id="input-web_onesignal_api_key" class="form-control form-control-alternative{{ $errors->has('web_onesignal_api_key') ? ' is-invalid' : '' }}" placeholder="{{ __('One Signal Rest Api Key for Web notification') }}" value="{{ $setting->web_onesignal_api_key }}" >
                                                        @if ($errors->has('web_onesignal_api_key'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('web_onesignal_api_key') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group row {{ $errors->has('web_onesignal_auth_key') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-web_onesignal_auth_key">{{ __('One Signal Auth Key') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="web_onesignal_auth_key" id="input-web_onesignal_auth_key" class="form-control form-control-alternative{{ $errors->has('web_onesignal_auth_key') ? ' is-invalid' : '' }}" placeholder="{{ __('One Signal Auth Key for Web notification') }}" value="{{ $setting->web_onesignal_auth_key }}" >
                                                        @if ($errors->has('web_onesignal_auth_key'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('web_onesignal_auth_key') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group row {{ $errors->has('web_onesignal_app_id') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-web_onesignal_app_id">{{ __('One Signal AppID') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="web_onesignal_app_id" id="input-web_onesignal_app_id" class="form-control form-control-alternative{{ $errors->has('web_onesignal_app_id') ? ' is-invalid' : '' }}" placeholder="{{ __('One Signal AppID for Web notification') }}" value="{{ $setting->web_onesignal_app_id }}" >
                                                        @if ($errors->has('web_onesignal_app_id'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('web_onesignal_app_id') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-primary mt-4">{{ __('Save') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>


                                    <div class="tab-pane" id="mail-v">
                                        <form method="post" action="{{url('mainAdmin/saveMailSettings')}}" autocomplete="off" enctype="multipart/form-data" >
                                            @csrf

                                            <h6 class="heading-small text-muted mb-4">{{ __("SMTP Setting") }}</h6>
                                            <div>
                                                <div class="form-group{{ $errors->has('mail_notification') ? ' has-danger' : '' }}">
                                                    <div class="row">
                                                        <div class="col-3"> <label class="form-control-label">{{ __('Enable Mail Notification') }}</label></div>
                                                        <div class="col-9">
                                                            <label class="custom-toggle">
                                                                <input type="checkbox" value="1" {{$setting->mail_notification == 1?'checked':''}} name="mail_notification" id="mail_notification">
                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row {{ $errors->has('mail_host') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-mail_host">{{ __('Mail Host') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="mail_host" required id="input-mail_host" class="form-control form-control-alternative{{ $errors->has('mail_host') ? ' is-invalid' : '' }}" placeholder="{{ __('Mail Host') }}" >
                                                        @if ($errors->has('mail_host'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('mail_host') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row {{ $errors->has('mail_port') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-mail_port">{{ __('Mail Port') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="mail_port" required id="input-mail_port" class="form-control form-control-alternative{{ $errors->has('mail_port') ? ' is-invalid' : '' }}" placeholder="{{ __('Mail Port') }}" >
                                                        @if ($errors->has('mail_port'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('mail_port') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row {{ $errors->has('mail_username') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-mail_username">{{ __('Mail UserName') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="mail_username" required id="input-mail_username" class="form-control form-control-alternative{{ $errors->has('mail_username') ? ' is-invalid' : '' }}" placeholder="{{ __('Mail UserName') }}" >
                                                        @if ($errors->has('mail_username'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('mail_username') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row {{ $errors->has('mail_password') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-mail_password">{{ __('Mail Password') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="mail_password" required id="input-mail_password" class="form-control form-control-alternative{{ $errors->has('mail_password') ? ' is-invalid' : '' }}" placeholder="{{ __('Mail Password') }}"  >
                                                        @if ($errors->has('mail_password'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('mail_password') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row {{ $errors->has('sender_email') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-sender_email">{{ __('Sender Email ID') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="sender_email" required id="input-sender_email" class="form-control form-control-alternative{{ $errors->has('sender_email') ? ' is-invalid' : '' }}" placeholder="{{ __('Sender Email ID') }}"  >
                                                        @if ($errors->has('sender_email'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('sender_email') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-primary mt-4">{{ __('Save') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="sms-v">
                                        <form method="post" action="{{url('mainAdmin/saveSMSSettings')}}" autocomplete="off" enctype="multipart/form-data" >
                                            @csrf

                                            <h6 class="heading-small text-muted mb-4">{{ __("SMS Setting") }}</h6>
                                            <div>
                                                <div class="form-group{{ $errors->has('sms_twilio') ? ' has-danger' : '' }}">
                                                    <div class="row">
                                                        <div class="col-3"> <label class="form-control-label">{{ __('Enable SMS Notification') }}</label></div>
                                                        <div class="col-9">
                                                            <label class="custom-toggle">
                                                                <input type="checkbox" value="1" {{$setting->sms_twilio == 1?'checked':''}} name="sms_twilio" id="sms_twilio">
                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>



                                                <div class="form-group row {{ $errors->has('twilio_account_id') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-twilio_account_id">{{ __('Twilio Account ID') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="twilio_account_id" id="input-twilio_account_id" class="form-control form-control-alternative{{ $errors->has('twilio_account_id') ? ' is-invalid' : '' }}" placeholder="{{ __('Twilio Account ID') }}" value="{{ $setting->twilio_account_id }}" >
                                                        @if ($errors->has('twilio_account_id'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('twilio_account_id') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row {{ $errors->has('twilio_auth_token') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-twilio_auth_token">{{ __('Twilio Auth Token') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="twilio_auth_token" id="input-twilio_auth_token" class="form-control form-control-alternative{{ $errors->has('twilio_auth_token') ? ' is-invalid' : '' }}" placeholder="{{ __('Twilio Auth Token') }}" value="{{ $setting->twilio_auth_token }}" >
                                                        @if ($errors->has('twilio_auth_token'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('twilio_auth_token') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group row {{ $errors->has('twilio_phone_number') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-twilio_phone_number">{{ __('Twilio Phone Number') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="twilio_phone_number" id="input-twilio_phone_number" class="form-control form-control-alternative{{ $errors->has('twilio_phone_number') ? ' is-invalid' : '' }}" placeholder="{{ __('Twilio Phone Number') }}" value="{{ $setting->twilio_phone_number }}" >
                                                        @if ($errors->has('twilio_phone_number'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('twilio_phone_number') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>


                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-primary mt-4">{{ __('Save') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane" id="map-v">
                                            <form method="post" action="{{url('mainAdmin/saveMapSettings')}}" autocomplete="off" enctype="multipart/form-data" >
                                                @csrf

                                                <h6 class="heading-small text-muted mb-4">{{ __("Map Setting") }}</h6>
                                                <div>
                                                    <div class="form-group row {{ $errors->has('map_key') ? ' has-danger' : '' }}">
                                                        <div class="col-3">
                                                            <label class="form-control-label" for="input-map_key">{{ __('Map Api Key') }}:</label>
                                                        </div>
                                                        <div class="col-9">
                                                            <input type="text" name="map_key" id="input-map_key" class="form-control form-control-alternative{{ $errors->has('map_key') ? ' is-invalid' : '' }}" placeholder="{{ __('Map api key') }}" value="{{ $setting->map_key }}" required>
                                                            @if ($errors->has('map_key'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('map_key') }}</strong>
                                                                </span>
                                                            @endif

                                                            <p class="mt-4" style="font-weight:400;font-size:15px;letter-spacing: .5px;"> {{ __('The Maps JavaScript API lets you customize maps with your own content and imagery for display on web pages and mobile devices. See')}} <a href="https://developers.google.com/maps/documentation/javascript/get-api-key">{{ __('Get API Key')}}</a> {{ __('for more information')}}.</p>
                                                        </div>
                                                    </div>

                                                    <div class="text-right">
                                                        <button type="submit" class="btn btn-primary mt-4">{{ __('Save') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                    </div>

                                    <div class="tab-pane" id="point-v">
                                        <form method="post" action="{{url('mainAdmin/savePointSettings')}}" autocomplete="off" enctype="multipart/form-data">
                                            @csrf

                                            <h6 class="heading-small text-muted mb-4">{{ __("Loyalty Point Setting") }}</h6>

                                            <div>
                                                <div class="form-group{{ $errors->has('enable_point') ? ' has-danger' : '' }}">
                                                    <div class="row">
                                                        <div class="col-3"> <label class="form-control-label">{{ __('Enable Loyalty Point System') }}</label>
                                                        </div>
                                                        <div class="col-9">
                                                            <label class="custom-toggle">
                                                                <input type="checkbox" value="1" {{$point->enable_point == 1?'checked':''}}
                                                                    name="enable_point" id="sms_twilio">
                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No"
                                                                    data-label-on="Yes"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group row {{ $errors->has('value_per_point') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-value_per_point">{{ __('Value of each point') }}</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="number" name="value_per_point" placeholder="Value of each point" value="{{$point->value_per_point}}"  id="input-value_per_point" class="form-control form-control-alternative{{ $errors->has('value_per_point') ? ' is-invalid' : '' }}" required>
                                                        @if ($errors->has('value_per_point'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('value_per_point') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row {{ $errors->has('max_order_for_point') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-max_order_for_point">{{ __('Maximum Order for Point') }}</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="number" name="max_order_for_point" placeholder="Maximum Order for Point" value="{{$point->max_order_for_point}}"  id="input-max_order_for_point" class="form-control form-control-alternative{{ $errors->has('max_order_for_point') ? ' is-invalid' : '' }}" required>
                                                        @if ($errors->has('max_order_for_point'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('max_order_for_point') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row {{ $errors->has('min_cart_value_for_point') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-min_cart_value_for_point">{{ __('Minimum cart value') }}</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="number" name="min_cart_value_for_point" placeholder="Minimum cart value" value="{{$point->min_cart_value_for_point}}"  id="input-min_cart_value_for_point" class="form-control form-control-alternative{{ $errors->has('min_cart_value_for_point') ? ' is-invalid' : '' }}" required>
                                                        @if ($errors->has('min_cart_value_for_point'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('min_cart_value_for_point') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row {{ $errors->has('max_redeem_amount') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-max_redeem_amount">{{ __('Maximum reedem Amount') }}</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="number" name="max_redeem_amount" placeholder="Maximum reedem Amount" value="{{$point->max_redeem_amount}}"  id="input-max_redeem_amount" class="form-control form-control-alternative{{ $errors->has('max_redeem_amount') ? ' is-invalid' : '' }}" required>
                                                        @if ($errors->has('max_redeem_amount'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('max_redeem_amount') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>



                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-primary mt-4">{{ __('Save') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="website-v">
                                        <form method="post" action="{{url('mainAdmin/saveWebContent')}}" autocomplete="off" enctype="multipart/form-data" >
                                            @csrf

                                            <h6 class="heading-small text-muted mb-4">{{ __("Web content Setting") }}</h6>
                                            <div class="web-content">

                                                <div class="form-group{{ $errors->has('terms_condition') ? ' has-danger' : '' }}">
                                                    <label class="form-control-label" for="input-terms_condition">{{ __('Terms & Condition') }}</label>
                                                    <textarea name="terms_condition" placeholder="Enter Content" rows="10" id="terms_condition" class="form-control web_editor form-control-alternative" >{{$setting->terms_condition}}</textarea>
                                                    @if ($errors->has('terms_condition'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('terms_condition') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="form-group{{ $errors->has('privacy_policy') ? ' has-danger' : '' }}">
                                                    <label class="form-control-label" for="input-privacy_policy">{{ __('Privacy Policy') }}</label>
                                                <textarea name="privacy_policy" placeholder="Enter Content" rows="10" id="privacy_policy" class="form-control web_editor form-control-alternative" >{{$setting->privacy_policy}}</textarea>
                                                    @if ($errors->has('privacy_policy'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('privacy_policy') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="form-group{{ $errors->has('about_us') ? ' has-danger' : '' }}">
                                                    <label class="form-control-label" for="input-about_us">{{ __('Content for About Us') }}</label>
                                                    <textarea name="about_us" placeholder="Enter Content" rows="10" id="about_us" class="form-control web_editor form-control-alternative" >{{$setting->about_us}}</textarea>
                                                    @if ($errors->has('about_us'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('about_us') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-primary mt-4">{{ __('Save') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="additional-v">
                                        <form method="post" action="{{url('mainAdmin/saveSettings')}}" autocomplete="off" enctype="multipart/form-data" >
                                            @csrf

                                            <h6 class="heading-small text-muted mb-4">{{ __("General Setting") }}</h6>
                                            <div>
                                                <div class="form-group row {{ $errors->has('currency') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-currency">{{ __('Currency') }}:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <select name="currency" id="input-currency" class="select2 form-control form-control-alternative{{ $errors->has('currency') ? ' is-invalid' : '' }}" required>
                                                            <option value="">Select Currency</option>
                                                            @foreach ($currency as $item)
                                                            <option value="{{$item->code}}"{{$setting->currency==$item->code?'Selected' : ''}}>{{$item->currency.' ( '.$item->symbol.' )'.' ( '.$item->country.' )'}}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('currency'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('currency') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>


                                                <div class="form-group row {{ $errors->has('request_duration') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-request_duration">{{ __('Request Duration') }} (in MS):</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="number" min="0" name="request_duration" placeholder="Enter request duratetion in Milliseconds" value="{{$setting->request_duration}}"  id="input-request_duration" class="form-control form-control-alternative{{ $errors->has('request_duration') ? ' is-invalid' : '' }}" required>


                                                        @if ($errors->has('request_duration'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('request_duration') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group row {{ $errors->has('default_driver_radius') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-default_driver_radius">{{ __('Default radius of Driver') }} (in KM):</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="number" min="0" name="default_driver_radius" placeholder="Enter default radius of Driver" value="{{$setting->default_driver_radius}}"  id="input-default_driver_radius" class="form-control form-control-alternative{{ $errors->has('default_driver_radius') ? ' is-invalid' : '' }}" required>


                                                        @if ($errors->has('default_driver_radius'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('default_driver_radius') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group row {{ $errors->has('primary_color') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-primary_color">{{ __('Frontend primary color') }}</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="color" name="primary_color" value="{{$setting->primary_color}}"  id="input-primary_color" class="form-control form-control-alternative{{ $errors->has('primary_color') ? ' is-invalid' : '' }}" required>

                                                        @if ($errors->has('primary_color'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('primary_color') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>


                                                <div class="form-group{{ $errors->has('sell_product') ? ' has-danger' : '' }}">
                                                    <div class="row">
                                                        <div class="col-3"> <label class="form-control-label">{{ __('Deliver Product of') }}</label></div>
                                                        <div class="col-9 row">
                                                            <div class="col-2">
                                                                <h5>{{ __('Food') }}</h5>
                                                                <label class="custom-toggle">
                                                                    <input type="radio" value="1" {{$setting->sell_product == 1?'checked':''}} name="sell_product" id="sell_product">
                                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-2">
                                                                <h5>{{ __('Grocery') }}</h5>
                                                                <label class="custom-toggle">
                                                                    <input type="radio" value="2" {{$setting->sell_product == 2?'checked':''}} name="sell_product" id="sell_product">
                                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-2">
                                                                <h5>{{ __('Both') }}</h5>
                                                                <label class="custom-toggle">
                                                                    <input type="radio" value="0" {{$setting->sell_product == 0?'checked':''}} name="sell_product" id="sell_product">
                                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-primary mt-4">{{ __('Save') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane {{ $status==0?'active':''}}" id="license-v">
                                        <form method="post" action="{{url('mainAdmin/saveLicense')}}" autocomplete="off" enctype="multipart/form-data">
                                            @csrf

                                            <h6 class="heading-small text-muted mb-4">{{ __("License setting") }}</h6>
                                            <div>

                                                <div class="form-group row {{ $errors->has('license_key') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-license_key">{{ __('License Key') }}</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" {{$status==1?'disabled':''}} name="license_key" placeholder="Enter License key" value="{{$setting->license_key}}"  id="input-license_key" class="form-control form-control-alternative{{ $errors->has('license_key') ? ' is-invalid' : '' }}" required>
                                                        @if ($errors->has('license_key'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('license_key') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group row {{ $errors->has('license_name') ? ' has-danger' : '' }}">
                                                    <div class="col-3">
                                                        <label class="form-control-label" for="input-license_name">{{ __('License name') }}</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="license_name" {{$status==1?'disabled':''}} placeholder="Enter License name" value="{{$setting->license_name}}"  id="input-license_name" class="form-control form-control-alternative{{ $errors->has('license_name') ? ' is-invalid' : '' }}" required>
                                                        @if ($errors->has('license_name'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('license_name') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-3"></div>
                                                    <div class="col-9">
                                                        @if(Session::has('error_msg'))
                                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                                            <strong>{{Session::get('error_msg')}}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($status==0)
                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-primary mt-4">{{ __('Save') }}</button>
                                                </div>
                                                @endif
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
