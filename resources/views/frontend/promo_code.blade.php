@extends('frontend.layouts.app')

@section('title','Promo Code')
@section('content')
<div class="container-fuild package_background pt-5" style="background-image: url('{{url('frontend/image/icon/Group 6465.png')}}')">
    <div class="container text-center text-white pt-5">
        <h3>Get upto</h3>
        <h1>50% of CASHBACK</h1>
    </div>
</div>
<div class="container pt-2 couponContainer">
    <h3 class="text-left">Coupons for use</h3>
    <hr class="hr">
    <div class="row">
        @foreach ($data as $key => $item)
        <div class="col-sm-12 col-xs-12 col-md-6 ">
            <div class="row couponList">
                <div class="col-lg-3 col-md-4 col-sm-2 promoIcon col-3 col-xl-2 col-4 p-3">
                    <div class="img">
                        <img src="{{ url('frontend/image/icon/Path 3233.png') }}" alt="">
                    </div>
                </div>
                <div class="col-8 promoContent col-md-8 col-lg-9 col-xl-10">
                    <h3>{{\App\CompanySetting::find(1)->name}}</h3>
                    @if ($data[$key]->type == 'amount')
                    <p class="text-secondary ml-5 pl-3">Get {{ $data[$key]->discount }}Rs. Discount</p>
                    @else
                    <p class="text-secondary ml-5 pl-3">Get {{ $data[$key]->discount }}% Discount</p>
                    @endif
                    <div class="row">
                        <div class="col" style="text-align: left">
                            <span class="coupen_textbox">{{ $data[$key]->code }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>
@endsection
