@extends('frontend.layouts.app')

@section('title','Grocery')
@section('content')
<div class="container-fuild grocery_background" style="background-image: url({{url('frontend/image/icon/2772844.png')}});">
    <div class="container grocery_container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="t1 pt-5 mt-5">
                    <h1 class="float-right text-right display-4">Eat The</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="t1">
                    <h1 class="float-right text-right display-3">Best & healthy</h1> <br>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div>
                    <hr class="float-right text-right grocery_hr">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fuild pt-5 bg-light p-2">
    <div class="container pb-5 couponContainer">
        <div class="row">
            <div class="col-md-9 col-sm-9 col-6 float-left">
                <div class="text-left index_display_title">Trending near you</div>
                <hr class="hr">
            </div>
            <div class="col-md-3 col-sm-3 col-6 float-right">
                <a href="{{url('grocery_item')}}">
                    <div class="mr-3 text-green link_view_all">View all
                        <i class="fa fa-chevron-right ml-3"></i>
                    </div>
                </a>
            </div>
        </div>
        <div class="row scrollContainer cartItem">
            @foreach ($data as $item)
            <div class="col-md-4 col-sm-6 col-lg-3 cartItemCol">
                <div class="offerProduct grocery_content h-100 text-left rounded-lg bg-white p-3">
                    <a href="{{ url('single_grocery/'.$item->id) }}">
                        <img src="{{ url('images/upload/'.$item->image) }}" width="200" class="mb-4 float-left"
                            height="200" alt=""><br>
                    </a>
                    <div class="t1">
                        <div class="font-weight-bold pb-5">
                            {{ $item->name }}<br>
                            {{ $item->weight }}gm<br>
                        </div>
                        @if (Session::get('GrocarycartData') == null)
                        <div class="row grocery_row">
                            <div class="col left_col text-left">
                                <span class="qty">
                                    <button class="minus" onclick="add_grocery_cart({{$item->id}},'minus')">-</button>
                                    <input type="text" value="0" id="{{'qty' . $item->id}}" name="qty" readonly
                                        disabled>
                                    <button onclick="add_grocery_cart({{$item->id}},'plus')">+</button>
                                </span>
                            </div>
                            <div class="col right_col text-right text-green">
                                {{ $currency }}<input type="text" class="text-green price"
                                    id="{{'price' . $item['id']}}" value="{{ $item->sell_price }}" class="form-control"
                                    readonly>
                            </div>
                        </div>
                        @else
                        @if(in_array($item->id, array_column(Session::get('GrocarycartData'), 'id')))
                        @foreach (Session::get('GrocarycartData') as $cartData)
                        @if($cartData['id'] == $item->id)
                        <div class="row">
                            <div class="col left_col text-left">
                                <span class="qty">
                                    <button class="minus" onclick="add_grocery_cart({{$item->id}},'minus')">-</button>
                                    <input type="text" value="{{$cartData['qty']}}" id="{{'qty' . $item->id}}"
                                        name="qty" readonly disabled>
                                    <button onclick="add_grocery_cart({{$item->id}},'plus')">+</button>
                                </span>
                            </div>
                            <div class="col right_col text-green text-right">
                                {{ $currency }}<input type="text" class="price text-green" id="{{'price' . $item->id}}"
                                    value="{{ $cartData['price'] }}" class="form-control" readonly>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        @else
                        <div class="row">
                            <div class="col left_col text-left">
                                <span class="qty">
                                    <button class="minus" onclick="add_grocery_cart({{$item->id}},'minus')">-</button>
                                    <input type="text" value="0" id="{{'qty' . $item->id}}" name="qty" readonly
                                        disabled>
                                    <button onclick="add_grocery_cart({{$item->id}},'plus')">+</button>
                                </span>
                            </div>
                            <div class="col right-col text-green text-right">
                                {{ $currency }}<input type="text" class="price text-green" id="{{'price' . $item->id}}"
                                    value="{{ $item->sell_price }}" class="form-control" readonly>
                            </div>
                        </div>
                        @endif
                        @endif
                        <input type="hidden" value="{{ $item->sell_price }}" id="{{'original_price' . $item['id']}}">
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="container pt-5 pb-3">
        <div class="row">
            <div class="col-md-6">
                <div class="text-center">
                    <img src="{{ url('frontend/image/icon/1280px-New_Seasons_Market_logo.svg.png') }}" alt=""> <br><br>
                    <h4 class="display-6"> Up to 50% off </h4>
                    <h4>Grab this offer and buy.</h4>
                </div>
            </div>
            <div class="col-md-6">
                <div class="float-right">
                    <img src="{{ url('frontend/image/icon/NoPath - Copy (15).png') }}" height="200" alt="">
                </div>
            </div>
        </div>
    </div>

    <div class="container pt-3">
        <div class="row">
            <div class="col-md-9 col-sm-9 col-6 float-left">
                <div class="mt-5 text-left index_display_title">stores near by you</div>
                <hr class="hr">
            </div>
            <div class="col-md-3 col-sm-3 col-6 float-right">
                <a href="{{ url('grocery_stores') }}">
                    <div class="mr-3 mt-5 text-green link_view_all">View all
                        <i class="fa fa-chevron-right ml-3"></i>
                    </div>
                </a>
            </div>
        </div>
        <div class="row scrollContainer">
            @foreach($grocarries_shop as $grocary_shop)
            <div class="col-md-4 col-sm-6 col-lg-3">
                <div class="offerProduct w-100 h-100 p-3 text-center rounded-lg bg-white">
                    <a href=" {{url('grocery_shop/'.$grocary_shop->id)}}" class="text-center">
                        <img src="{{ url('images/upload/'.$grocary_shop->image) }}" class="rounded-lg mb-4" width="175"
                            height="200" alt=""></a><br>
                    <div class="text-left font-weight-bold">{{ $grocary_shop->name }}</div>
                    <div class="text-left font">
                        {{ $grocary_shop->category }}
                        <img src="{{ url('frontend/image/icon/icon map.png') }}" alt="">
                        {{ $grocary_shop->locationData['name'] }}
                        <br>
                        {{ $grocary_shop->address }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="container pt-5">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-12 float-left">
                <div class="mt-5 text-left index_display_title">Discover by category</div>
                <hr class="hr">
            </div>
        </div>

        <div class="row scrollContainer listOffers">
            @foreach ($grocery_categories as $grocery_category)
            <div class="col-md-3 col-sm-6 col-lg-2">
                <a href="{{ url('category_item/'.$grocery_category->id) }}">
                    <img src="{{ url('images/upload/'.$grocery_category->image) }}" width="100" height="100"
                        class="rounded-circle" alt="">
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
