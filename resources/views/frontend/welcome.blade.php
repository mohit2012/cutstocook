<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Laravel
                </div>

                <div class="links">
                    <a href="https://laravel.com/docs">Docs</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://blog.laravel.com">Blog</a>
                    <a href="https://nova.laravel.com">Nova</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://vapor.laravel.com">Vapor</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
            </div>
        </div>
    </body>
</html>


{{-- <div class="col-md-4 col-sm-6 col-lg-3 pb-4">
    <div class="offerProduct grocery_content w-100 h-100 text-left rounded-lg bg-white p-3"><img
            src="http://192.168.0.177:10/image/upload/download.jpg" width="200" class="mb-4 rounded-lg" height="200"
            alt=""><br>
        <div class="offerproduct">
            <div class="t1">
                <div class="t1">
                    <div class="font-weight-bold pb-5">rice<br>120KGgm<br></div>
                    <div class="row grocery_row">
                        <div class="col left_col text-left"><span class="qty"><button
                                    onclick="add_grocery_cart(7,`minus`)" class="minus">-</button><input type="text"
                                    value="0" id="qty7" name="qty" readonly="readonly" disabled="disabled"><button
                                    onclick="add_grocery_cart(7,`plus`)">+</button></span></div>
                        <div class="col right_col text-right">$<input type="text" id="price7" value="4"
                                readonly="readonly" class="price"></div><input type="hidden" value="4"
                            id="original_price7">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-lg-3 pb-4">
            <div class="offerProduct grocery_content w-100 h-100 text-left rounded-lg bg-white p-3"><img
                    src="http://192.168.0.177:10/image/upload/ashirvad.jpg" width="200" class="mb-4 rounded-lg"
                    height="200" alt=""><br>
                <div class="offerproduct">
                    <div class="t1">
                        <div class="t1">
                            <div class="font-weight-bold pb-5">Ashirwaad Atta<br>5 KGgm<br></div>
                            <div class="row grocery_row">
                                <div class="col left_col text-left"><span class="qty"><button
                                            onclick="add_grocery_cart(8,`minus`)" class="minus">-</button><input
                                            type="text" value="0" id="qty8" name="qty" readonly="readonly"
                                            disabled="disabled"><button
                                            onclick="add_grocery_cart(8,`plus`)">+</button></span></div>
                                <div class="col right_col text-right">$<input type="text" id="price8" value="98"
                                        readonly="readonly" class="price"></div><input type="hidden" value="98"
                                    id="original_price8">
                            </div>
                        </div>
                    </div>
                </div> --}}