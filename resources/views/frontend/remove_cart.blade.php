@extends('frontend.layouts.app')

    @section('title','Remove cart')
@section('content')

<div class="alert text-center remove_cart_alert">
        <img src="{{ url('frontend/image/icon/Group 6313.png') }}" alt="">your order is cancel successfully..!
</div>
<center class="pt-5 mt-5">
    <img src="{{ url('frontend/image/icon/Group 6587.png') }}" alt="">
    <h3>Your cart is empty</h3>
    <a href="{{ url('/') }}" class="text-danger">go to home page</a>
</center>
@endsection
