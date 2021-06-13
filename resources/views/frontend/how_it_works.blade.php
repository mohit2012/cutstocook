@extends('frontend.layouts.app')

@section('title','How It Works')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-12 col-lg-12">
            <div class="mt-5 text-left terms_text mr-2 pl-3">How It Works</div>
        </div>
    </div>
</div>
<div class="container mt-5">
    <div class="row mt-2">
        <div class="col-md-12 col-12 col-lg-12">
            <div class="mt-1">{{ $how_works }}</div>
        </div>
    </div>
</div>
@endsection
