@extends('frontend.layouts.app')

@section('title','Grocery shop Information')
@section('content')

<div class="container-fuild"
    style="background:linear-gradient( rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7) ), url({{ url('images/upload/'.$data->cover_image) }});background-size: cover;height: 400px;background-position:center top;">
    <div class="container couponContainer">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                <div class="row content">
                    <div class="col-md-2 col-sm-2 col-4">
                        <img src="{{ url('images/upload/'.$data->image) }}" width="100" height="100"
                            class="rounded-lg float-right" alt="">
                    </div>
                    <div class="col-md-6 col-sm-6 col-4 pt-3">
                        <div class="text-white font-weight-bold shopName">{{ $data->name }}</div>
                        <?php
                            $starttime = $data->open_time;
                            $endtime = $data->close_time;
                            $check = \Carbon\Carbon::now()->setTimezone('Asia/Kolkata')->between($starttime, $endtime);
                        ?>
                        @if ($check == true)
                        <div class="text-white">Open Now | {{ $data->open_time }} -
                            {{$data->close_time}}
                        </div>
                        @else
                        <div class="text-danger">closed | {{ $data->open_time }} - {{$data->close_time}} </div>
                        @endif
                    </div>
                    <div class="col-md-4 col-sm-4 col-4">
                        <input type="text" class="float-right h-50 w-100 search_grocery rounded-pill"
                            placeholder="search..">
                        <input type="hidden" id="grocery_shop" value="{{ $data->id }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-light container-fluid">
    <div class="container pt-5 couponContainer">
        <div class="row">
            <div class="col-md-9 col-sm-9 col-6 float-left">
                <div class="text-left index_display_title">Trending near you</div>
                <hr class="hr">
            </div>
            <div class="col-md-3 col-sm-3 col-6 float-right">
                <a href="{{url('grocery_item')}}">
                    <div class="mr-3 text-green link_view_all">View all                        
                        <i class="fa fa-chevron-right ml-3"></i>
                    </div>
                </a>
            </div>
        </div>

        <div class="row  cartItem">
            @foreach ($grocerries_item as $item)
            <div class="col-md-4 col-sm-6 col-lg-3 pb-4">
                <div class="offerProduct grocery_content w-100 h-100 text-left rounded-lg bg-white p-3">
                    <a href="{{ url('single_grocery/'.$item ->id) }}">
                        <img src="{{ url('images/upload/'.$item->image) }}" width="200" class="mb-4" height="200"
                            alt=""></a>
                    <br>
                    <div class="t1">
                        <div class="font-weight-bold pb-5">
                            {{ $item->name }}<br>
                            {{ $item->weight }}gm<br>
                        </div>
                        @if (Session::get('GrocarycartData') == null)
                        <div class="row grocery_row">
                            <div class="col left_col text-left">
                                <span class="qty">
                                    <button class="minus" onclick="add_grocery_cart({{$item->id}},'minus')">-</button>
                                    <input type="text" value="0" id="{{'qty' . $item->id}}" name="qty" readonly
                                        disabled>
                                    <button onclick="add_grocery_cart({{$item->id}},'plus')">+</button>
                                </span>
                            </div>
                            <div class="col right_col text-right text-green">
                                {{ $currency }}<input type="text" class="text-green price"
                                    id="{{'price' . $item['id']}}" value="{{ $item->sell_price }}" class="form-control"
                                    readonly>
                            </div>
                        </div>
                        @else
                        @if(in_array($item->id, array_column(Session::get('GrocarycartData'),'id')))
                        @foreach (Session::get('GrocarycartData') as $cartData)
                        @if($cartData['id'] == $item->id)
                        <div class="row grocery_row">
                            <div class="col text-left">
                                <span class="qty">
                                    <button class="minus" onclick="add_grocery_cart({{$item->id}},'minus')">-</button>
                                    <input type="text" value="{{$cartData['qty']}}" id="{{'qty' . $item->id}}"
                                        name="qty" readonly disabled>
                                    <button onclick="add_grocery_cart({{$item->id}},'plus')">+</button>
                                </span>
                            </div>
                            <div class="col text-green text-left">
                                {{ $currency }}<input type="text" class="price text-green" id="{{'price' . $item->id}}"
                                    value="{{ $cartData['price'] }}" class="form-control" readonly>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        @else
                        <div class="row grocery_row">
                            <div class="col text-left">
                                <span class="qty">
                                    <button class="minus" onclick="add_grocery_cart({{$item->id}},'minus')">-</button>
                                    <input type="text" value="0" id="{{'qty' . $item->id}}" name="qty" readonly
                                        disabled>
                                    <button onclick="add_grocery_cart({{$item->id}},'plus')">+</button>
                                </span>
                            </div>
                            <div class="col text-green text-left">
                                {{ $currency }}<input type="text" class="price text-green" id="{{'price' . $item->id}}"
                                    value="{{ $item->sell_price }}" class="form-control" readonly>
                            </div>
                            <input type="hidden" value="{{ $item->sell_price }}"
                                id="{{'original_price' . $item['id']}}">
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row pt-5 mt-2">
            <div class="col-md-12 col-sm-12">
                <div class="text-left index_display_title">Discover by category</div>
                <hr class="hr">
            </div>
        </div>

        <div class="row">
            @foreach ($grocery_category as $grocery_category)
            <div class="col-md-4 col-sm-4 col-4">
                <a href="{{ url('category_item/'.$grocery_category->id) }}">
                    <img src="{{ url('images/upload/'.$grocery_category->image) }}" width="100" height="100"
                        class="rounded-circle" alt=""></a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
