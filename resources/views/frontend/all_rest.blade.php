@extends('frontend.layouts.app')

@section('title','All Restaurant Information')
@section('content')
<div class="container couponContainer pt-3">
    <div class="row">
        <div class="col-md-9 col-sm-9 col-9">
            <h3 class="t1 text-left index_display_title">Discover all restaurants</h3>
            <hr class="hr">
        </div>
        <div class="col-md-3 col-sm-3 col-3">
            <div class="t1 text-right filter">
                <img src="{{ url('frontend/image/icon/controls.png') }}" alt="">&nbsp;FILTERS
            </div>

            <div class="card float-right filter_card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <label class="checkbox-button">
                                <input type="radio" class="checkbox-button__input text-left" id="chkTopRate"
                                    value="top rated" name="chkFilter" onclick="setFilter(event,'top rated')">
                                <span class="checkbox-button__control"></span>
                                <span class="checkbox-button__label text-left">Top rated</span>
                            </label>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <label class="checkbox-button">
                                <input type="radio" class="checkbox-button__input text-right" id="chkOpenNow"
                                    value="open now" name="chkFilter" onclick="setFilter(event,'open now')">
                                <span class="checkbox-button__control"></span>
                                <span class="checkbox-button__label text-right">open now</span>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <label class="checkbox-button">
                                <input type="radio" class="checkbox-button__input text-left" id="chkCostLow"
                                    value="cost low" name="chkFilter" onclick="setFilter(event,'cost low')">
                                <span class="checkbox-button__control"></span>
                                <span class="checkbox-button__label text-left">Cost low to high</span>
                            </label>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <label class="checkbox-button">
                                <input type="radio" class="checkbox-button__input text-right" id="chkExclusive"
                                    value="exclusive" name="chkFilter" onclick="setFilter(event,'exclusive')">
                                <span class="checkbox-button__control"></span>
                                <span class="checkbox-button__label text-right">Exclusive</span>
                            </label>
                        </div>
                    </div>

                    <label class="checkbox-button">
                        <input type="radio" class="checkbox-button__input text-left" id="chkHighLow" value="cost high"
                            name="chkFilter" onclick="setFilter(event,'cost high')">
                        <span class="checkbox-button__control"></span>
                        <span class="checkbox-button__label">Cost high to low</span>
                    </label>

                </div>
            </div>
        </div>
    </div>
    <div class="row listOffers" id="resultData">
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
                    @endif</div>
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
    </div>
</div>

@endsection
