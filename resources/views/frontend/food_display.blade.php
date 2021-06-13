@extends('frontend.layouts.app')

@section('title','Food Information')
@section('content')
<div class="container couponContainer pt-5">
    <div class="row">
        <div class="col-md-9 col-sm-9 col-6 float-left">
            <div class="text-left index_display_title">Discover All restaurants</div>
            <hr class="hr">
        </div>
        <div class="col-md-3 col-sm-3 col-6 float-right">
            <a href="{{ url('all_restaurants') }}">
                <div class="text-green link_view_all text-right">view all
                    <i class="fa fa-arrow-right"></i></div>
            </a>
        </div>
    </div>

    <div class="row scrollContainer listOffers">
        @foreach ($shops as $shop)
        <div class="col-md-4 col-sm-6 col-lg-3">
            <a href="{{url('restaurants_information/'.$shop->id)}}">
            <img src="{{ url('images/upload/'.$shop->image) }}" width="200" class="rounded-lg mb-4" height="200" alt=""></a>
            <div class="offerproduct text-left">
                <div class="font-weight-bold">
                    {{ $shop->name }}
                @if ($shop->veg == 1)
                <img src="{{ url('frontend/image/icon/veg.png') }}" alt=""><br>
                @else
                <img src="{{ url('frontend/image/icon/non-veg.png') }}" alt=""><br>
                @endif</div>
                <div class="productcontent font">
                    <div class="offer_item">{{ $shop->itemNames }}</div>
                    <img src="{{url('frontend/image/icon/Icon ionic-ios-star.png')}}" alt="">{{ $shop->rate }}
                    <img src="{{ url('frontend/image/icon/Ellipse 17.png') }}" class="ml-1 mr-1" alt="">{{ $shop->delivery_time }}Min. <br>
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

<div class="container couponContainer pt-5">
    <div class="row">
        <div class="col-md-9 col-sm-9 col-6 float-left">
            <h3 class="text-left index_display_title">Largest offers on food only for you</h3>
            <hr class="hr">
        </div>
        <div class="col-md-3 col-sm-3 col-6 float-right">
            <a href="{{ url('offer') }}">
                <div class="text-green link_view_all text-right">view all
                    <i class="fa fa-arrow-right"></i></div>
                </div>
            </a>
    </div>

    <div class="row scrollContainer listOffers pt-5">
        @foreach($offers as $offer)
        <div class="col-md-4 col-sm-6 col-lg-3">
            <div class="offerProduct text-left">
                <img src="{{url('images/upload/'.$offer->image)}}" class="rounded-lg mb-4" width="200px" height="200px" alt="">
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

<div class="container couponContainer pt-5">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-12 float-left">
            <h3 class="text-left index_display_title">Discover By Category</h3>
            <hr class="hr">
        </div>
    </div>

    <div class="row scrollContainer listOffers pt-5">
        @foreach ($categoris as $category)
        <div class="col-md-4 col-sm-6 col-lg-3">
            <div class="offerProduct text-left">
                <a href="{{ url('category_shop/'.$category->id) }}">
                <img src="{{ url('images/upload/'.$category->image)}}" class="rounded-lg" width="200" height="200"
                    alt=""></a>
                <br>
                <b>{{ $category->name }}</b><br>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="container couponContainer pt-5">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-12 float-left">
            <h3 class="text-left index_display_title">Popular on this week</h3>
            <hr class="hr">
        </div>
    </div>

    <div class="row scrollContainer listOffers pt-5">
        @foreach ($foods as $food)
       
        <div class="col-md-4 col-sm-6 col-lg-3">
            
            <div class="offerProduct text-left">
                <a href="{{url('single_food/'.$food->id)}}"> <img src="{{url('images/upload/'.$food->image)}}" height="200" width="200" class="rounded-lg mb-4" alt=""></a>
                <br>
                <div class="font-weight-bold">{{ $food->name }}
                @if ($food->isVeg == 1)
                <img src="{{url('frontend/image/icon/veg.png')}}" alt=""><br>
                @else
                <img src="{{url('frontend/image/icon/non-veg.png')}}" alt=""><br>
                @endif</div>
                <div class="productcontent font">
                     {{ $food->shop['name'] }}<br>
                    {{ $food->category['name'] }} <br>
                    @if ($food->isNew == 1)
                        <span class="exclusive_food text-white green">Exclusive</span>
                    @endif
                    @if ($food->isPopular == 1)
                        <span class="featured_food  text-white red">Trending</span>
                    @endif
                </div>
            </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
<div class="container couponContainer pt-5">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-12 float-left">
            <h3 class="text-left index_display_title">Pocket friendly</h3>
            <hr class="hr">
        </div>
    </div>

    <div class="row scrollContainer listOffers pt-5">
    @foreach ($pocket_friendly as $pocket)
        <div class="col-md-4 col-sm-6 col-lg-3">
            
            <div class="offerProduct text-left">
                <a href="{{url('single_food/'.$pocket->id)}}"><img src="{{url('images/upload/'.$pocket->image)}}" height="200px" width="200px" class="rounded-lg mb-4" alt=""></a>
                <br>
                <b>{{ $pocket->shop['name'] }}</b>
              
                @if ($pocket->isVeg == 1)
                <img src="{{url('frontend/image/icon/veg.png')}}" alt=""><br>
                @else
                <img src="{{url('frontend/image/icon/non-veg.png')}}" alt=""><br>
                @endif
                {{ $pocket->name }}<br>
                Price : {{ $currency }}{{ $pocket->price }}
            </div>
            
        </div>
        @endforeach
    </div>
</div>
@endsection
