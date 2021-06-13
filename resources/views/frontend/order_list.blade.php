@extends('frontend.layouts.app')

@section('title','Order List')
@section('content')
@if (Session::get('cartData') == null && Session::get('GrocarycartData') == null)
<div class="container">
    <h3 class="text-center pt-5">No Items Available in Cart..!!</h3>
</div>
@else
<div class="container couponContainer">
    <div class="row">
        <div class="col-md-6 col-sm-6 col-6 pt-5 pb-4">
            <div class="text-left your_orders">your orders</div>
        </div>
        <div class="col-md-6 col-sm-6 col-6">
            <a href="{{url('remove_cart')}}" class="btn text-danger float-right font-weight-normal">Remove all
                Items..</a>
        </div>
    </div>
    <div class="row cartItem">
        <div class="col-md-12 col-sm-12 col-12">

            @if(Session::get('cartData') != null)
                @foreach (Session::get('cartData') as $item)
                    <div class="row" style="margin-bottom: 7px;">
                        @if (count($item)>0)
                        @if (isset($item['id']))
                        @if ($item['type'] == 'item')
                            <div class="col-md-6 col-sm-6 text-left">
                                <div>{{  App\Item::find($item['id'])->name }}</div>
                            </div>
                            <div class="col-md-6 col-sm-6 text-left">
                                <div class="row p-3">
                                    <div class="col-md-8 col-sm-10 col-10">
                                        @php
                                        $original_price_item = App\Item::find($item['id'])->price;
                                        @endphp
                                        <input type="hidden" id="{{'original_price' . $item['id']}}"
                                            value="{{ $original_price_item }}">
                                        {{ $currency }}<input type="text" class="price" id="{{'price' . $item['id']}}"
                                            value="{{ $item['price'] }}" class="form-control" readonly>
                                        <span class="qty">
                                            <button onclick="update_cart({{$item['id']}},'item','minus')" class="{{'minus'.$item['id']}}">-</button>
                                            <input type="text" value="{{$item['qty']}}" id="{{'qty' . $item['id']}}" name="qty"
                                                readonly disabled>
                                            <button onclick="update_cart({{$item['id']}},'item','plus')">+</button>
                                        </span>
                                    </div>
                                    <div class="col-md-4 col-sm-2 col-2">
                                        <img src="{{ url('frontend/image/icon/delete.png') }}" class="float-right"
                                            onclick="removeSingleItem({{$item['id']}})" class="delete_address" alt="">
                                    </div>
                                </div>
                            </div>
                        @endif
                        @endif
                        @if(isset($item['combo_id']))
                            @if ($item['type'] == 'combo')
                                <div class="col-md-6 col-sm-6 text-left">
                                    <div>{{ App\Package::find($item['combo_id'])->name }}</div>
                                </div>
                                <div class="col-md-6 col-sm-6 text-left">
                                    <div class="row p-3">
                                        <div class="col-md-8 col-sm-10 col-10">
                                            @php
                                            $original_price_combo = App\Package::find($item['combo_id'])->package_price;
                                            @endphp
                                            <input type="hidden" id="{{'original_price' . $item['combo_id']}}"
                                                value="{{ $original_price_combo }}">
                                            {{ $currency }}<input type="text" class="price" id="{{'price' . $item['combo_id']}}"
                                                value="{{ $item['price'] }}" class="form-control" readonly>
                                            <span class="qty">
                                                <button onclick="update_cart({{$item['combo_id']}},'combo','minus')" class="{{'minus'.$item['combo_id']}}">-</button>
                                                <input type="text" value="{{$item['qty']}}" id="{{'qty' . $item['combo_id']}}" name="qty" readonly disabled>
                                                <button onclick="update_cart({{$item['combo_id']}},'combo','plus')">+</button>
                                            </span>
                                        </div>
                                        <div class="col-md-4 col-sm-2 col-2">
                                            <img src="{{ url('frontend/image/icon/delete.png') }}" class="float-right" onclick="removeSingleItem({{$item['combo_id']}})" class="delete_address" alt="">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        @endif
                    </div>
                @endforeach
            @endif
            @if (Session::get('GrocarycartData') != null)
                @foreach (Session::get('GrocarycartData') as $item)
                    <div class="row" style="margin-bottom: 7px;">
                        @if (count($item)>0)
                            <div class="col-md-6 col-sm-6 text-left">
                                <div>{{ App\GroceryItem::find($item['id'])->name }}</div>
                            </div>
                            <div class="col-md-6 col-sm-6 text-left">
                                <div class="row p-3">
                                    <div class="col-md-8 col-sm-10 col-10">
                                        @php
                                            $original_price_item = App\GroceryItem::find($item['id'])->sell_price;
                                        @endphp
                                        <input type="hidden" id="{{'original_price' . $item['id']}}" value="{{ $original_price_item }}">
                                        {{ $currency }}<input type="text" class="price" id="{{'price' . $item['id']}}"
                                            value="{{ $item['price'] }}" class="form-control" readonly>
                                        <span class="qty">
                                            <button class="{{'minus'.$item['id']}}" onclick="add_grocery_cart({{$item['id']}},'minus')">-</button>
                                            <input type="text" value="{{$item['qty']}}" id="{{'qty' . $item['id']}}" name="qty" readonly disabled>
                                            <button onclick="add_grocery_cart({{$item['id']}},'plus')">+</button>
                                        </span>
                                    </div>
                                    <div class="col-md-4 col-sm-2 col-2">
                                        <img src="{{ url('frontend/image/icon/delete.png') }}" class="float-right" onclick="removeSingleItem({{$item['id']}})" class="delete_address" alt="">
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<div class="container-fuild mt-5 green text-white w-100 p-3">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-6 float-left">
                @if(Session::get('cartData') != null)
                    Your orders ({{ count(Session::get('cartData')) }})
                @endif
                @if (Session::get('GrocarycartData'))
                    Your orders ({{ count(Session::get('GrocarycartData')) }})
                @endif
            </div>
            <div class="col-md-6 col-sm-6 float-right">
                @php
                    $price = 0;
                @endphp

                @if(Session::get('cartData') != null)
                    @foreach (Session::get('cartData') as $item)
                        @php
                            $price += intval($item['price'])
                        @endphp
                    @endforeach
                @endif
                @if(Session::get('GrocarycartData') != null)
                    @foreach (Session::get('GrocarycartData') as $item)
                        @php
                        $price += intval($item['price'])
                        @endphp
                    @endforeach
                @endif
                {{ $currency }} <span class="order_list_price">{{ $price }}</span>
                @if (Auth::check())
                    <a href="{{url('order_confirmation')}}">
                        <img src="{{ url('frontend/image/icon/Group 5789.png') }}" alt="">
                    </a>
                @else
                    <img src="{{ url('frontend/image/icon/Group 5789.png') }}" id="continue" alt="" data-toggle="modal" data-target="#exampleModalCenter">
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
                                                    style="text-transform: none" autofocus placeholder="Enter email...">
                                                <p class="email text-danger" role="alert"></p>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col">
                                                <input id="password" type="password" class="form-control" name="password"
                                                    required autocomplete="current-password"
                                                    placeholder="Enter password...">
                                                <p class="password text-danger" role="alert"></p>
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
                                                        @if (Route::has('password.request'))
                                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                                            {{ __('Forgot Your Password?') }}
                                                        </a>
                                                        @endif
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
                                                <a href="{{url('register')}}" class="text-green login_button"
                                                    data-toggle="modal" data-target="#register">SIGNUP</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection



