@extends('frontend.layouts.app')

@section('title','All Restaurant Information')
@section('content')
<div class="container couponContainer pt-3">
    <div class="row">
        <div class="col-md-9 col-sm-9 col-9">
            <h3 class="t1 text-left">restaurants</h3>
            <hr class="hr">
        </div>
    </div>
    <div class="row listOffers" id="resultData">
        @if (count($shops)>0)
        @foreach ($shops as $shop)
        <div class="col-md-4 col-6 col-sm-6 col-lg-3 pb-4">
            <a href="{{url('restaurants_information/'.$shop->id)}}">
                <img src="{{ url('images/upload/'.$shop->image) }}" width="200" class="rounded-lg mb-4" height="200"
                    alt="">
            </a>
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
                    <img src="{{url('frontend/image/icon/Icon ionic-ios-star.png')}}" alt="">{{ $shop->rate }}
                    <img src="{{ url('frontend/image/icon/Ellipse 17.png') }}" class="ml-1 mr-1"
                        alt="">{{ $shop->delivery_time }}Min. <br>
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
        @else
        <h4>No restaurants found...</h4>
        @endif
    </div>
</div>

@endsection
