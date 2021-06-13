<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/png" href="{{url('/images/upload/'.App\CompanySetting::find(1)->favicon)}}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ App\CompanySetting::find(1)->name}}</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"> --}}
    {{-- <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"> --}}
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{url('frontend/css/FoodLans.css')}}">
    <link rel="stylesheet" href="{{url('frontend/css/custom.css')}}">

    @php
    $web_onesignal_app_id = App\Setting::find(1)->web_onesignal_app_id;
    @endphp
    <script>
        var OneSignal = window.OneSignal || [];
        if(OneSignal.length>0){
            setTimeout(() =>
            {
                OneSignal.getRegistrationId(function(status)
                {
                    console.log('status',status);
                });
            }, 2000);
            OneSignal.push(function()
            {
                var id = '<?php echo $web_onesignal_app_id; ?>'
                OneSignal.init({
                    appId: id,
                });
                OneSignal.getUserId().then(function(userId)
                {
                    console.log("OneSignal User ID:", userId);
                    $.ajax({
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type:"POST",
                        url:window.location.origin+'/getUserToken',
                        data:
                        {
                            id:userId,
                        },
                        success: function(result)
                        {
                            console.log('result ',result)
                        },
                        error: function(err)
                        {
                            console.log('err ',err)
                        }
                    });
                });
            });
        }
    </script>
</head>

<body>

    <?php	$primary_color = \App\Setting::find(1)->primary_color; ?>
	<style>
		:root{
                --primary_color: <?php echo $primary_color ?>;
                --light_primary_color: <?php echo $primary_color.'5c' ?>;
            }
    </style>

    <div class="foodhub_preloader_holder">
        <div id="cooking">
            <div id="area">
                <div id="sides">
                    <div id="pan"></div>
                    <div id="handle"></div>
                </div>
                <div id="pancake">
                    <div id="pastry"></div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" value="{{url('/')}}" id="mainurl">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    @php
                    $logo = App\CompanySetting::find(1)->logo;
                    @endphp
                    <img src="{{url('images/upload/'.$logo)}}" alt="" width="150px">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item ml-2 text-left">
                            <a class="nav-link {{ request()->is('/')  ? 'active' : ''}}" href="{{ url('/') }}"><b
                                    class="t1">home</b>
                            </a>
                        </li>
                        <li class="nav-item ml-2 text-left {{ request()->is('food')  ? 'active' : ''}}">
                            <a class="nav-link" href="{{ url('food') }}">
                                <img src="{{url('frontend/image/Icon/fast-food.png')}}" alt="">
                                <b class="t1">food</b>
                            </a>
                        </li>
                        <li class="nav-item ml-2 text-left {{ request()->is('grocery')  ? 'active' : ''}}">
                            <a class="nav-link" href="{{url('grocery')}}">
                                <img src="{{url('frontend/image/Icon/Outline.png')}}" alt="">
                                <b class="t1">Grocery</b>
                            </a>
                        </li>
                        <li class="nav-item ml-2 text-left {{ request()->is('offer')  ? 'active' : ''}} ">
                            <a class="nav-link" href="{{ url('offer') }}"><b class="t1">Offers</b>
                            </a>
                        </li>
                        <li class="nav-item ml-2 text-left {{ request()->is('promo_code')  ? 'active' : ''}}">
                            <a class="nav-link" href="{{ url('promo_code') }}"><b class="t1">Promo code</b>
                            </a>
                        </li>
                        <li class="nav-item ml-2 text-left {{ request()->is('order_list')  ? 'active' : ''}}">
                            <a class="nav-link" href="{{ url('order_list') }}"><b class="t1">Cart</b></a>
                        </li>
                    </ul>

                    <ul class="navbar-nav">
                        @guest
                        <li class="nav-item text-left">

                            <a class="nav-link l1" href="{{ url('login') }}" data-toggle="modal"
                                data-target="#exampleModalCenter">
                                <b class="t1 text-left">
                                    <img src="{{ url('frontend/image/icon/login icon.png') }}" alt="">
                                    Login
                                </b>
                            </a>

                            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Login</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form>
                                                <div class="form-group row">
                                                    <div class="col">
                                                        <input id="email" type="email" class="form-control" name="email"
                                                            value="{{ old('email') }}" required autocomplete="email"
                                                            style="text-transform: none" autofocus
                                                            placeholder="Enter email...">
                                                        <p class="user_email text-danger" role="alert"></p>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col">
                                                        <input id="password" type="password" class="form-control"
                                                            name="password" required autocomplete="current-password"
                                                            placeholder="Enter password...">
                                                        <p class="user_password text-danger" role="alert"></p>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-md-6 text-secondary">
                                                        <div class="form-check">
                                                            <input class="form-check-input float-left" type="checkbox"
                                                                name="remember" id="remember"
                                                                {{ old('remember') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="remember">
                                                                {{ __('Remember Me') }}
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 text-secondary">
                                                        <div class="form-check float-right">
                                                            <label class="form-check-label" for="remember">
                                                                <a class="btn btn-link"
                                                                    href="{{ url('forgot_password') }}">Forgot
                                                                    Your Password?</a>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-md-12 text-center">
                                                        <button type="button" onclick="user_login()"
                                                            class="btn w-100 bg-blue text-white">
                                                            {{ __('Login') }}
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-md-12 text-center">
                                                        Don't have an account?
                                                        <a href="{{url('user_register')}}"
                                                            class="text-green login_button" data-toggle="modal"
                                                            data-target="#register"
                                                            onclick="clearValidation()">SIGN UP</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <div class="modal fade" id="register" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Register</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="user_register_form">
                                            <div class="form-group row">
                                                <div class="col">
                                                    <input id="name" type="text" class="form-control" name="name"
                                                        required placeholder="Enter user name" autofocus
                                                        style="text-transform: none">
                                                    <p class="name text-danger"></p>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col">
                                                    <input id="register_email" type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        name="email" value="{{ old('email') }}" required
                                                        autocomplete="email" placeholder="Enter email" autofocus
                                                        style="text-transform: none">
                                                    <p class="email text-danger"></p>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col">
                                                    <input id="register_password" type="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        name="password" required placeholder="password"
                                                        autocomplete="current-password" style="text-transform: none">

                                                    <p class="password text-danger"></p>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col">
                                                    <input id="phone" type="text" class="form-control" name="phone"
                                                        required placeholder="Phone number"
                                                        style="text-transform: none">
                                                    <p class="phone text-danger"></p>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-12 text-center">
                                                    <button type="button" onclick="user_register()"
                                                        class="btn w-100 bg-blue text-white">Register</button>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-12 text-center">
                                                    you have already account?
                                                    <a class="text-green sign_up_button" href="{{ url('login') }}"
                                                        data-toggle="modal" data-target="#exampleModalCenter">Login</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link text-left dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ url('user_details') }}">My profile</a>
                                <a class="dropdown-item" href="{{ url('settings') }}">Settings</a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="MainContent">
            @yield('content')
        </div>
        @include('frontend.footer')

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

        <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
        <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        @if(Request::is('order_confirmation') || Request::is('grocery_order_confirmation') || Request::is('settings') || Request::is('/'))
            <script type="text/javascript"
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDOz5oWyuWCeyh-9c1W5gexDzRakcRP-eM&callback=initAutocomplete&libraries=places"
                async defer></script>
            <script src="{{ url('frontend/js/map.js') }}"></script>
        @endif
        @if(Request::is('order_confirmation') || Request::is('grocery_order_confirmation'))
            <?php
                $payment_sendbox_key = App\PaymentSetting::find(1)->paypalSendbox;
                $currency = App\Setting::find(1)->currency
            ?>
            <script src="https://www.paypal.com/sdk/js?client-id={{$payment_sendbox_key}}&currency={{$currency}}" data-namespace="paypal_sdk"></script>
            <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
            <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
            <script src="{{ url('frontend/js/payment.js') }}"></script>
            <script src="https://js.paystack.co/v1/inline.js"></script>

        @endif
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/perfect-scrollbar.min.js"></script>
        <script src="{{ url('frontend/js/custom.js') }}"></script>
    </div>
</body>

</html>
