@extends('frontend.layouts.app')

@section('title','About Us')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12 col-lg-12">
                <div class="mt-5 text-left terms_text mr-2 pl-3">About Us</div>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="row mt-2">
            <div class="col-md-12 col-12 col-lg-12">
                <div class="mt-1">{{ $about_us }}</div>
            </div>
        </div>
    </div>
@endsection
