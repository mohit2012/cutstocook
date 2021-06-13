@extends('frontend.layouts.app')

@section('title','Foodeli')
@section('content')
@if (session('cart_empty'))
<div class="alert alert-danger">
    {{ session('cart_empty') }}
</div>
@endif

<div class="container-fuild header text-light p-2">
    <div class="container couponContainer">
        <div class="row">
            <div class="col mt-5 pt-5 text-center">
                <img src="{{url('images/upload/'.App\CompanySetting::find(1)->logo)}}" style="height:150px;width:150px;" alt="">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-12 mt-2 pt-2 text-center">
                <div class="discover_all">Discover the flavors of {{ App\CompanySetting::find(1)->name }}</div>
                <div class="row form-group searchRow">
                    <input type="hidden" name="lat" value="22.3039" id="lat">
                    <input type="hidden" name="lang" value="70.8022" id="lang">
                    <div class="col-md-5 offset-lg-2 col-lg-4 col-sm-12">
                        <input type="text" name="search_meal" id="search_meal" class="form-control searchInput"
                            placeholder="search your meal">
                        {{-- <img src="{{url('frontend/image/icon/search.png')}}" class="searchIcon" alt=""> --}}
                        <i class="fa fa-search searchIcon text-dark"></i>
                    </div>
                    <div class="col-md-5 col-sm-12 col-lg-4">
                        <input type="text" name="autocomplete" id="address" class="form-control locationInput"
                            placeholder="set your location">
                        {{-- <img src="{{url('frontend/image/icon/pin.png')}}" class="locationIcon" alt=""> --}}
                        <i class="fa fa-map-marker locationIcon text-dark"></i>
                    </div>
                    <div class="col-md-2 col-sm-12 col-lg-2">
                        <button class="btn btn-success green" onclick="search()">Search</button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<div class="container-fuild p-2 pb-5 search">
    <div class="container mb-5 couponContainer">
        <div class="row">
            <div class="col-md-9 col-sm-9 col-6 float-left">
                <div class="mt-5 text-left index_display_title">Discover The food</div>
                <hr class="hr">
            </div>
            <div class="col-md-3 col-sm-3 col-6 float-right">
            <a href="{{ url('food') }}">
                <div class="mt-5 mr-3 text-green link_view_all">Explorer all
                    <i class="fa fa-chevron-right ml-3"></i>
                </div>
            </a>
            </div>
        </div>

        <div class="row scrollContainer listOffers">
            @foreach ($shops as $shop)
            <div class="col-md-4 col-6 col-sm-6 col-lg-3">
                <a href="{{url('restaurants_information/'.$shop->id)}}">
                    <img src="{{ url('images/upload/'.$shop->image) }}" width="200" height="200" class="rounded-lg mb-4"
                        alt=""></a>
                <div class="offerproduct text-left">
                    <div class="font-weight-bold">{{ $shop->name }}
                        @if ($shop->veg == 1)
                        <img src="{{ url('frontend/image/icon/veg.png') }}" alt=""><br>
                        @else
                        <img src="{{ url('frontend/image/icon/non-veg.png') }}" alt=""><br>
                        @endif
                    </div>
                    <div class="productcontent font">
                        <div class="offer_item">{{ $shop->itemNames }}</div>
                        <i class="fa fa-star text-warning pr-1"></i>{{ $shop->rate }}
                        <span class="delivery_time">{{ $shop->delivery_time }}Min</span> <br>
                        @if ($shop->featured == 1)
                        <span class="featured_food text-white red">Trending</span>
                        @endif
                        @if ($shop->exclusive == 1)
                        <span class="exclusive_food text-white green">Exclusive</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="container-fluid bg-light pt-5 pb-5 p-2">
    <div class="container couponContainer">
        <div class="row">
            <div class="col-md-9 col-sm-9 col-6 float-left">
                <div class="text-left index_display_title">Largest offers on food only for you</div>
                <hr class="hr">
            </div>
            <div class="col-md-3 col-sm-3 col-6 float-right">
                <a href="{{ url('offer') }}">
                    <div class="mr-3 text-green link_view_all">Explorer all
                        <i class="fa fa-chevron-right ml-3"></i>
                    </div>
                </a>
            </div>
        </div>
        <div class="row scrollContainer listOffers">
            @foreach($offers as $offer)
            <div class="col-md-4 col-sm-6 col-lg-3">
                <div class="offerProduct text-left">
                    <img src="{{url('images/upload/'.$offer->image)}}" class="rounded-lg mb-4" width="200px"
                        height="200px" alt="">
                    <br>
                    <b>{{ $offer->shopName }}</b><br>
                    <div class="font">
                        <div class="offer_item">{{ $offer->itemsName }}</div>
                        <div class="text-green">Get off<strike>{{ $currency }}{{ $offer->total_price }}</strike>
                            {{ $currency }}{{ $offer->package_price }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="container-fluid pt-3 p-2 pb-5">
    <div class="container couponContainer mb-5">
        <div class="row">
            <div class="col-md-9 col-sm-9 col-6">
                <div class="mt-5 text-left index_display_title">Discover the grocery</div>
                <hr class="hr">
            </div>
            <div class="col-md-3 col-sm-3 col-6 float-right">
                {{-- <a href="{{ url('grocery') }}">
                    <i class="fa fa-chevron-right ml-3"></i>
                </a> --}}
                <a href="{{ url('grocery') }}">
                    <div class="mr-3 text-green link_view_all">Explorer all
                        <i class="fa fa-chevron-right ml-3"></i>
                    </div>
                </a>
            </div>
        </div>
        <div class="row scrollContainer listOffers">
            @foreach ($grocarries as $grocary)
            <div class="col-md-4 col-sm-6 col-lg-3">
                <div class="offerProduct text-left">
                    <a href="{{ url('single_grocery/'.$grocary->id) }}">
                        <img src="{{ url('images/upload/'.$grocary->image) }}" class="rounded-lg mb-4" width="200px"
                            height="200px" alt=""></a> <br>
                    <div class="font-weight-bold">{{ $grocary->name }} </div>
                    <div class="font">{{ $grocary->category['name'] }} <br>
                        {{ $grocary->weight }} g <br>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="container-fluid pb-5 pt-5 bg-light p-2">
    <div class="container couponContainer">
        <div class="row">
            <div class="col-md-9 col-sm-9 col-6 float-left">
                <div class="text-left index_display_title">Grocery stores near by you</div>
                <hr class="hr">
            </div>
            <div class="col-md-3 col-sm-3 col-6 float-right">
                <a href="{{ url('grocery_stores') }}">
                    <div class="mr-3 text-green link_view_all">Explorer all
                        <i class="fa fa-chevron-right ml-3"></i>
                    </div>
                </a>
            </div>
        </div>

        <div class="row scrollContainer listOffers">
            @foreach ($grocarries_shop as $grocary_shop)
            <div class="col-md-4 col-sm-6 col-lg-3">
                <div class="offerProduct text-left">
                    <a href="{{ url('grocery_shop/'.$grocary_shop->id) }}">
                        <img src="{{ url('images/upload/'.$grocary_shop->image) }}" class="rounded-lg mb-4"
                            width="200px" height="200px" alt=""></a><br>
                    <div class="font-weight-bold">{{ $grocary_shop->name }}</div>
                    <div class="productContent font">
                        {{ $grocary_shop->category }}
                        {{-- <img src="{{ url('frontend/image/Icon/icon map.png') }}" alt=""> --}}
                        <i class="fa fa-map-marker web-icon"></i>
                        {{ $grocary_shop->locationData['name'] }}
                        <br>
                        {{ $grocary_shop->address }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
