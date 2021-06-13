<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{url('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{url('css/FoodLans.css')}}">
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{url('frontend/image/Icon/Logo.png')}}" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('index') }}"><b class="t1">home</b>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <img src="frontend/image/Icon/fast-food.png" alt="">
                                <b class="t1">food</b>

                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <img src="frontend/image/Icon/Outline.png" alt="">
                                <b class="t1">grossary</b>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href=""><b class="t1">Offers</b>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href=""><b class="t1">Promo code</b>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><b class="t1">Help Center</b>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><b class="t1"> Cart</b>
                            </a>
                        </li>

                    </ul>

                    <ul class="navbar-nav ml-auto">
                        @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                        <li class="nav-item">
                            {{-- <a class="nav-link" href="{{ route('register') }}">{{ __('Registe  r') }}</a> --}}
                            <a class="nav-link" href="{{ url('register') }}">Register</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
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

        <div class="container-fluid login_image">
            <div class="row">
                <div class="container pt-5 mt-5">
                    <div class="row justify-content-center">
                        {{-- <div class="row"> --}}
                        <div class="col-md-4">
                            <div class="card rounded-lg">
                                <div class="row">
                                    <div class="col-md-4 pl-4 pt-3 float-left">{{ __('Login') }}</div>
                                    <div class="col-md-4 pl-4 pt-3"></div>
                                    <div class="col-md-4 pl-5 pt-3 float-right"><img
                                            src="{{ url('frontend/image/cross icon.png') }}" alt=""></div>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ url('confirm_login') }}">
                                        @csrf
                                        <div class="form-group row">
                                            <div class="col">
                                                <input id="email" type="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    name="email" value="{{ old('email') }}" required
                                                    autocomplete="email" autofocus>
                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col">
                                                <input id="password" type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" required autocomplete="current-password">

                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col offset-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="remember"
                                                        id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                    <label class="form-check-label" for="remember">
                                                        {{ __('Remember Me') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row mb-0">
                                            <div class="col-md-8 offset-md-4">
                                                <button type="submit" class="btn btn-primary">
                                                    {{ __('Login') }}
                                                </button>
                                            </div>
                                        </div>

                                        <div>
                                        <a href="{{url('register_user')}}" class="text-dark">
                                                Don't have an account sign up??
                                            </a>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
