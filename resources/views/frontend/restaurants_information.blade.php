@extends('frontend.layouts.app')

@section('title','Restaurant Information')
@section('content')
<div class="container-fuild restaurantInfo"
    style="background:linear-gradient( rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7) ), url({{ url('images/upload/'.$data->image) }});background-size: cover;height: 400px;background-position:center top;">
    <div class="container">
        <div class="row pt-3">
            <div class="col">
                <img src="{{ url('frontend/image/icon/Group 5501.png') }}" class="float-right bookmark" onclick="addbookmark()"
                    alt="">
            </div>
        </div>
        <div class="row content">
            <div class="col-md-10 col-sm-10 col-xs-12">
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
            <div class="col-md-2 col-sm-2 col-xs-12">
                <div class="text-left displayRating">
                    <span class="review">{{ $data->rate }}</span><br>
                    <span class="rate_display">{{ $data->totalReview }}</span><br>
                    <span class="rate text-secondary">Review</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container couponContainer bg-light p-3">
    <div class="row pl-5">
        <div class="col-md-1 col-sm-1 col-12 text-left">
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
        <div class="col-md-8 col-sm-8 col-12 text-left">
            <a href="{{ url('restaurant_profile/'.$data->id) }}" class="view_rest_profile">View Restaurant profile</a>
        </div>
    </div>
</div>
<input type="hidden" value="{{ $data->id}}" id="shop_id">
<div class="container rest_container couponContainer pt-4">
    @if (count($data->item) > 0)
    <div class="row">
        <div class="col-md-8 col-12 col-sm-12">
            <h4 class="t1 text-left">Best Seller</h4>
            <hr class="hr">
        </div>

        <div class="col-md-4 col-12 col-sm-12">
            <div class="text-left">
                <span class="t1 mt-2 ">Veg</span>
                <label class="switch">
                    <input type="radio" id="chkVeg" name="veg" onchange="itemfilter('veg')" class="switch">
                    <span class="slider round"></span>
                </label>
                <span class="t1">Non-Veg</span>
                <label class="switch">
                    <input type="radio" id="chkNonVeg" name="veg" onchange="itemfilter('non-veg')" class="switch">
                    <span class="slider round"></span>
                </label>
                <span class="t1">All</span>
                <label class="switch">
                    <input type="radio" id="chkAll" name="veg" onchange="itemfilter('all')" class="switch" checked>
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>

    <div class="row rest_row cartItem" id="data">
        @foreach ($data->item as $item)
        <div class="col-md-6 col-sm-12 col-12 pt-4">
            <div class="card rest_card rounded-lg shadow">
                <div class="card-body" id="cardBody{{$item->id}}">
                    <div class="row">
                        <div class="col-md-5 col-5 col-lg-4 col-xl-3">
                            <img src="{{ url('images/upload/'.$item->image) }}" class="float-left rounded-lg" width="116"
                                height="116" alt="">
                        </div>
                        <div class="col-md-5 col-4 col-lg-5 col-xl-6">
                            <div class="text-left rest_item_name">{{ $item->name }}</div>
                            <div class="text-left">{{$item->categoryName}}</div>
                        </div>
                        <div class="col-md-2 col-3 col-lg-3 col-xl-3">
                            <div class="sessionUpdateitem{{$item->id}}">
                                @if(Session::get('cartData') == null)
                                <span class="text-green rest_price">{{ $currency }}{{ $item->price }}</span>
                                <input type="button" id="addcartitem{{$item->id}}" value="Add + "
                                    onclick="addtocart({{$item->id}},{{$item->price}},'item')"
                                    class="btn btn-outline-info mt-5 t1 float-right">
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
            </div>
        </div>
        @endforeach
    </div>
    </h4>
    @else
    <h3>No Items Available...</h3>
    @endif
</div>
<div class="container couponContainer pt-4">
    @if(count($data->combo) > 0)
    <h4 class="t1 text-left">
        Special Combo
        <hr class="hr">
        <div class="row cartItem" id="data">
            @foreach ($data->combo as $combo)
            <div class="col-md-12 pt-4">
                <div class="card h-100 w-100 shadow">
                    <div class="card-body" id="cardBody{{$combo->id}}">
                        <div class="row">
                            <div class="col-md-5 col-5 col-lg-4 col-xl-3">
                                <img src="{{ url('images/upload/'.$combo->image) }}" class="rounded-lg" width="116"
                                    height="116" alt="">
                            </div>
                            <div class="col-md-5 col-4 col-lg-5 col-xl-6">
                                <div class="text-left rest_item_name">{{ $combo->name }}</div>
                                <div class="text-left">{{$combo->itemsName}}</div>
                            </div>
                            <div class="col-md-2 col-3 col-lg-3 col-xl-3">
                                <div class="float-right sessionUpdatecombo{{$combo->id}}">
                                    @if(Session::get('cartData') == null)
                                    <span
                                        class="text-green rest_price">{{ $currency }}{{ $combo->package_price }}</span><br>
                                    <input type="button" id="addcartcombo{{$combo->id}}" value="Add + "
                                        onclick="addtocart({{$combo->id}},{{$combo->package_price}},'combo')"
                                        class="btn btn-outline-info t1 mt-5">
                                    @else
                                    @if(in_array($combo->id, array_column(Session::get('cartData'), 'combo_id')))
                                    @foreach (Session::get('cartData') as $cartitem)
                                    @if ($cartitem['type'] == 'combo')
                                    <div style="margin-bottom:7px;">
                                        @if($cartitem['combo_id'] == $combo->id)
                                        <div class="cartItem">
                                            @php
                                            $original_price = App\Package::find($cartitem['combo_id'])->package_price;
                                            @endphp
                                            <input type="hidden" id="{{'original_price' . $cartitem['combo_id']}}"
                                                value="{{ $original_price }}">
                                            <span class="text-green rest_price">
                                                {{ $currency }}<input type="text" class="price text-green"
                                                    id="{{'price' . $cartitem['combo_id']}}"
                                                    value="{{ $cartitem['price'] }}" class="form-control" readonly
                                                    disabled>
                                            </span><br>
                                            <span class="qty float-right mt-5">
                                                <button class="{{'minus' . $cartitem['combo_id']}}"
                                                    onclick="update_cart({{$cartitem['combo_id']}},'combo','minus')">-</button>
                                                <input type="text" value="{{$cartitem['qty']}}"
                                                    id="{{'qty' . $cartitem['combo_id']}}" name="qty" readonly disabled>
                                                <button
                                                    onclick="update_cart({{$cartitem['combo_id']}},'combo','plus')">+</button>
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                    @endforeach
                                    @else
                                    <span
                                        class="text-green rest_price pt-3">{{ $currency }}{{ $combo->package_price }}</span><br>
                                    <input type="button" id="addcart{{$combo->id}}" value="Add + "
                                        onclick="addtocart({{$combo->id}},{{$combo->package_price}},'combo')"
                                        class="btn btn-outline-info mt-5 t1">
                                    @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </h4>
    @else
    <h3>No Combos Available...</h3>
    @endif
</div>
@endsection
