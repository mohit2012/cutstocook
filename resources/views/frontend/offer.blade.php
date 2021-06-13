@extends('frontend.layouts.app')

@section('title','Offer')
@section('content')

<div class="container pt-3">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-12">
            <div class="mt-5 text-left index_display_title">Largest offers on food only for you</div>
            <hr class="hr">
        </div>
    </div>
    <div class="row listOffers">
        @foreach($offers as $offer)
        <div class="col-md-4 col-sm-6 col-lg-3">
            <div class="offerProduct">
                <img src="{{url('images/upload/'.$offer->image)}}" class="rounded-lg mb-4" width="200" height="200" alt="">
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

@endsection
