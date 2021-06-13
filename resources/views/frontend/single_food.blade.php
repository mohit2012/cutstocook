@extends('frontend.layouts.app')

@section('title','Food Profile')
@section('content')
<div class="container-fluid bg-light">
    <div class="container couponContainer">
        <div class="row">
            <div class="col-md-3 col-sm-12 col-12 p-4">
                <img src="{{url('images/upload/'.$item->image)}}" width="200" height="300" alt="">
            </div>
            <div class="col-md-9 col-sm-12 col-12 p-4">
                <div class="row cartItem">
                    <div class="col-md-6 col-sm-6 col-6">
                        <div class="index_display_title text-left">{{ $item->name }}</div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-6">
                        <div class="t1 text-left">
                            <div class="sessionUpdateitem{{$item->id}}">
                                @if(Session::get('cartData') == null)
                                <span class="text-green rest_price">{{ $currency }}{{ $item->price }}</span>
                                <input type="button" id="addcartitem{{$item->id}}" value="Add + " onclick="addtocart({{$item->id}},{{$item->price}},'item')" class="btn btn-outline-info mt-5 t1 float-right">
                                @else
                                @if(in_array($item->id, array_column(Session::get('cartData'), 'id')))
                                @foreach (Session::get('cartData') as $cartitem)
                                @if (count($cartitem)>0)
                                <div style="margin-bottom: 7px;">
                                    @if ($cartitem['type'] == 'item')
                                    @if($cartitem['id'] == $item->id)
                                    <div class="cartItem">
                                        @php
                                        $original_price_item = App\Item::find($cartitem['id'])->price;
                                        @endphp
                                        <input type="hidden" id="{{'original_price' . $cartitem['id']}}"
                                            value="{{ $original_price_item }}">
                                        <span class="text-green rest_price">
                                            {{ $currency }}<input type="text" class="price text-green"
                                                id="{{'price' . $cartitem['id']}}" value="{{ $cartitem['price'] }}"
                                                class="form-control" readonly>
                                        </span>
                                        <span class="qty float-right mt-5">
                                            <button class="{{'minus' . $cartitem['id']}}"
                                                onclick="update_cart({{$cartitem['id']}},'item','minus')">-</button>
                                            <input type="text" value="{{$cartitem['qty']}}"
                                                id="{{'qty' . $cartitem['id']}}" name="qty" readonly disabled>
                                            <button onclick="update_cart({{$cartitem['id']}},'item','plus')">+</button>
                                        </span>
                                    </div>
                                    @endif
                                    @endif
                                </div>
                                @endif
                                @endforeach
                                @else
                                <span class="text-green rest_price float-right">{{ $currency }}{{ $item->price }}</span>
                                <input type="submit" id="addcart" value="Add + "
                                    onclick="addtocart({{$item->id}},{{$item->price}},'item')"
                                    class="btn btn-outline-info mt-5 t1 float-right">
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    {{-- <div class="col-md-6 col-sm-6 col-6">
                        <div class="item_total text-secondary text-left">{{ $item->weight }} Gm</div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-6">
                        <div class="item_total text-green text-left">
                            {{ $currency }}{{ $item->price }}
                        </div>
                    </div> --}}
                </div>
                <div class="row py-5">
                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="single_grocery text-secondary text-left">{{ $item->description }}</div>
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
                        @if (count($shop_review)>0)
                            <div class="col-md-12 col-sm-12 p-3">
                                @foreach($shop_review as $review)
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
