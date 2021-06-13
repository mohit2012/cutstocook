@extends('frontend.layouts.app')

@section('title','Grocery Profile')
@section('content')
<div class="container-fluid bg-light">
    <div class="container couponContainer">
        <div class="row">
            <div class="col-md-3 col-sm-12 col-12 p-4">
                <img src="{{url('images/upload/'.$groceryitem->image)}}" width="200" height="300" alt="">
            </div>
            <div class="col-md-9 col-sm-12 col-12 p-4">
                <div class="row cartItem">
                    <div class="col-md-6 col-sm-6 col-6">
                        <div class="index_display_title text-left">{{ $groceryitem->name }}</div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-6">
                        <div class="t1 text-left">
                            @if (Session::get('GrocarycartData') == null)
                            <span class="qty">
                                <button class="minus"
                                    onclick="add_grocery_cart({{$groceryitem->id}},'minus')">-</button>
                                <input type="text" value="0" id="{{'qty' . $groceryitem->id}}" name="qty" readonly
                                    disabled>
                                <button onclick="add_grocery_cart({{$groceryitem->id}},'plus')">+</button>
                            </span>
                            {{ $currency }}<input type="text" class="price" id="{{'price' . $groceryitem    ['id']}}"
                                value="{{ $groceryitem->sell_price }}" class="form-control" readonly>
                            @else
                            @if(in_array($groceryitem->id, array_column(Session::get('GrocarycartData'), 'id')))

                            @foreach (Session::get('GrocarycartData') as $cartData)
                            @if($cartData['id'] == $groceryitem->id)
                            <span class="qty">
                                <button class="minus"
                                    onclick="add_grocery_cart({{$groceryitem->id}},'minus')">-</button>
                                <input type="text" value="{{$cartData['qty']}}" id="{{'qty' . $groceryitem->id}}"
                                    name="qty" readonly disabled>
                                <button onclick="add_grocery_cart({{$groceryitem->id}},'plus')">+</button>
                            </span>
                            {{ $currency }}<input type="text" class="price" id="{{'price' . $groceryitem->id}}"
                                value="{{ $cartData['price'] }}" class="form-control" readonly>
                            @endif
                            @endforeach
                            @else
                            <span class="qty">
                                <button class="minus"
                                    onclick="add_grocery_cart({{$groceryitem->id}},'minus')">-</button>
                                <input type="text" value="0" id="{{'qty' . $groceryitem->id}}" name="qty" readonly
                                    disabled>
                                <button onclick="add_grocery_cart({{$groceryitem->id}},'plus')">+</button>
                            </span>
                            {{ $currency }}<input type="text" class="price" id="{{'price' . $groceryitem->id}}"
                                value="{{ $groceryitem->sell_price }}" class="form-control" readonly>
                            @endif
                            @endif
                            <input type="hidden" value="{{ $groceryitem->sell_price }}"
                                id="{{'original_price' . $groceryitem['id']}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-6">
                        <div class="item_total text-secondary text-left">{{ $groceryitem->weight }} Gm</div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-6">
                        <div class="item_total text-green text-left">
                            {{ $currency }}{{ $groceryitem->sell_price }}
                        </div>
                    </div>
                </div>
                <div class="row p-5">
                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="single_grocery text-secondary text-left">{{ $groceryitem->description }}</div>
                    </div>
                </div>
                <div class="row pt-4">
                    <div class="grocery_border"></div>
                </div>
                <div class="row pt-4">
                    <div class="col">
                        <div class="sub_category_name reviews pl-2">Reviews</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 p-1">
                        @if (count($grocery_shop_review)>0)
                            <div class="col-md-12 col-sm-12 p-3">
                                @foreach($grocery_shop_review as $review)
                                <div class="row couponList grocery_review">
                                    <div class="col-md-2 col-sm-4 text-center">
                                        <img src="{{ url('images/upload/'.$review->customer->image) }}" class="user_image rounded-circle" width="60"
                                            height="60" alt="">
                                    </div>
                                    <div class="col-md-5 col-sm-4 grocery_review_customer">
                                        <p class="text-left grocery_review_user t1">{{ $review->customer->name }}</p>
                                        <p class="text-secondary grocery_review_user_create">{{$review->created_at->diffForHumans()}}</p>
                                    </div>
                                    <div class="col-md-5 col-sm-4 grocery_review_star">
                                        @php
                                        $star = $review->rate
                                        @endphp
                                        @for ($i = 1; $i < 6; $i++)
                                            @if ($i <=$star)
                                                <i class="fa fa-star text-green" aria-hidden="true"></i>
                                            @else
                                                <i class="fa fa-star-o text-green" aria-hidden="true"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-10">
                                        <p class="text-left text-secondary">{{ $review->message }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div>
                                <h4 class="t1 pt-2 text-center">No Reviews Yet..</h4>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
