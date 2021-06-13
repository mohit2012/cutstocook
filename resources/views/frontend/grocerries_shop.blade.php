@extends('frontend.layouts.app')

@section('title','All Grocery Information')
@section('content')
<div class="container-fuild bg-light w-100 h-100 pb-5">
    <div class="container pt-5 mb-5">
        <div class="row">
            <div class="col-md-4 col-sm-4 col-4">
                <div class="text-left index_display_title">Stores</div>
                <hr class="hr">
            </div>
            <div class="col-md-4 col-sm-4 col-4">
                <div class="float-right">
                    <input type="text" class="form-control border grocery_store_search float-right rounded-pill"
                        placeholder="which Stores you Find">
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-4">
                <div class="float-right">
                    <h4 class="t1 grocery_filter"><img src="{{ url('frontend/image/icon/controls.png') }}" alt="">FILTERS</h4>
                    <div class="grocary_filter_card card float-right">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <label class="checkbox-button">
                                        <input type="radio" class="checkbox-button__input" id="chkTopRate"
                                            value="top rated" name="chkfilter"
                                            onclick="setGroceryFilter(event,'top rated')">
                                        <span class="checkbox-button__control"></span>
                                        <span class="checkbox-button__label">Top rated</span>
                                    </label>
                                </div>
                                <div class="col">
                                    <label class="checkbox-button">
                                        <input type="radio" class="checkbox-button__input" id="chkOpenNow"
                                            value="open now" name="chkfilter"
                                            onclick="setGroceryFilter(event,'open now')">
                                        <span class="checkbox-button__control"></span>
                                        <span class="checkbox-button__label">open now</span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label class="checkbox-button">
                                        <input type="radio" class="checkbox-button__input" id="chkMostPopular"
                                            value="most popular" name="chkfilter"
                                            onclick="setGroceryFilter(event,'Most Popular')">
                                        <span class="checkbox-button__control"></span>
                                        <span class="checkbox-button__label">Most Popular</span>
                                    </label>
                                </div>
                                <div class="col">
                                    <label class="checkbox-button">
                                        <input type="radio" class="checkbox-button__input" id="chkArrivedAtShop"
                                            value="arrived at shop" name="chkfilter"
                                            onclick="setGroceryFilter(event,'arrived at shop')">
                                        <span class="checkbox-button__control"></span>
                                        <span class="checkbox-button__label">Arrived At Shop</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row grocery_data">
            @foreach($grocerries_shop as $grocary_shop)
            <div class="col-md-4 col-sm-6 col-lg-3 pb-4">
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
</div>
@endsection
