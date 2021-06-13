@extends('frontend.layouts.app')

@section('title','Restaurant Profile')
@section('content')
<div class="container-fuild"
    style="background:linear-gradient( rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7) ), url({{ url('images/upload/'.$data->image) }});background-size: cover;height: 400px;background-position:center top;">
    <div class="container couponContainer pb-5 content">
        <div class="text-light text-left">
            <h3>{{ $data->name }}</h3>
            <h6>{{ $data->ItemNames }}</h6>
            <h6>{{ $data->address }} , {{ $data->locationData['name'] }} , {{ $data->pincode }}</h6>
            <?php
                $starttime = $data->open_time;
                $endtime = $data->close_time;
                $check = \Carbon\Carbon::now()->setTimezone('Asia/Kolkata')->between($starttime, $endtime);
            ?>
            @if ($check == true)
            <div class="text-green">Open Now | {{ $data->open_time }} -
                {{$data->close_time}}
            </div>
            @else
            <div class="text-danger">closed | {{ $data->open_time }} - {{$data->close_time}} </div>
            @endif
        </div>
    </div>
</div>

<div class="container couponContainer bg-light p-3">
    <div class="row pl-5 text-center">
        <div class="offset-md-4 col-md-1 col-sm-1 col-12 text-left">
            <img src="{{url('frontend/image/icon/Icon ionic-ios-star.png')}}" width="22.29" height="20.7" alt="">
            <span class="rest_info_text">{{ $data->rate }}</span><br>
            <span class="text-secondary">{{ $data->totalreview }}+Rating</span>
        </div>
        <div class="col-md-1 col-sm-1 col-12 text-center pt-3">
            <img src="{{ url('frontend/image/icon/Ellipse 22.png') }}" class="text-center" width="14" height="14" alt="">
        </div>
        <div class="col-md-2 col-sm-2 col-12 text-left">
            <span class="rest_info_text text-center">{{ $data->delivery_time }}Mins. </span>
            <span class="text-secondary text-center">Delivery Time</span>
        </div>
    </div>
</div>

<div class="container p-5 font text-center">
    {{ $data->description }} <br>
</div>

<div class="container p-5">
    <h4 class="text-left">Photos</h4>
    <hr class="hr">
    @if (count($data->item) > 0)
    <h4 class="t1 text-center">
        <div class="row">
            @foreach ($data->item as $item)
            <div class="col-md-4 col-6 col-sm-6 col-lg-3 pt-4">
                <img src="{{ url('images/upload/'.$item->image) }}" class="rounded" width="200" height="200" alt="">
            </div>
            @endforeach
        </div>
    </h4>
    @else
    <h3>No Items Available...</h3>
    @endif
</div>

<div class="container couponContainer p-5">
    <h4 class="text-left">reviews</h4>
    <hr class="hr">
    <div class="row">
        @foreach ($reviews as $review)
        <div class="col-md-6 col-sm-12 p-3">
            <div class="row couponList grocery_review">
                <div class="col-md-2 col-sm-4 text-center">
                    <img src="{{ url('images/upload/'.$review->customer->image) }}" class="user_image rounded-circle"
                        width="60" height="60" alt="">
                </div>
                <div class="col-md-5 col-sm-4 grocery_review_customer">
                    <p class="text-left grocery_review_user t1">{{ $review->customer->name }}</p>
                    <p class="text-secondary grocery_review_user_create">{{$review->created_at->diffForHumans()}}</p>
                </div>
                <div class="col-md-5 col-sm-4 grocery_review_star">
                    @php
                    $star = $review->rate
                    @endphp
                    @for ($i = 1; $i < 6; $i++) @if ($i <=$star) <i class="fa fa-star text-green" aria-hidden="true">
                        </i>
                        @else
                        <i class="fa fa-star-o text-green" aria-hidden="true"></i>
                        @endif
                        @endfor
                </div>
                <div class="col-md-2 col-sm-2">
                </div>
                <div class="col-md-10 col-sm-10">
                    <p class="text-left text-secondary">{{ $review->message }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</div>
@endsection
