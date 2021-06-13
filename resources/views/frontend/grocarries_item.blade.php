@extends('frontend.layouts.app')

@section('title','Grocery Item')
@section('content')
<div class="container-fuild bg-light">
    <div class="container pb-5 pt-5 couponContainer">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-6">
                <div class="text-left index_display_title">Trending near you</div>
                <hr class="hr">
            </div>
            <div class="col-md-6 col-sm-6 col-6">
                <div class="float-right">
                    <input type="text" class="form-control border grocery_item_search float-right rounded-pill"
                        placeholder="which items you want">
                </div>
            </div>
        </div>
        <div class="row grocery_Item_data cartItem" id="resultData">
            @foreach ($grocarries_item as $item)
            <div class="col-md-4 col-sm-6 col-lg-3 pb-4">
                <div class="offerProduct grocery_content w-100 h-100 text-left rounded-lg bg-white p-3">
                    <a href="{{ url('single_grocery/'.$item->id) }}">
                        <img src="{{ url('images/upload/'.$item->image) }}" width="200" class="mb-4" height="200"
                            alt=""><br>
                    </a>
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
                        @if(in_array($item->id, array_column(Session::get('GrocarycartData'), 'id')))
                        @foreach (Session::get('GrocarycartData') as $cartData)
                        @if($cartData['id'] == $item->id)
                        <div class="row grocery_row">
                            <div class="col left_col text-left">
                                <span class="qty">
                                    <button class="minus" onclick="add_grocery_cart({{$item->id}},'minus')">-</button>
                                    <input type="text" value="{{$cartData['qty']}}" id="{{'qty' . $item->id}}"
                                        name="qty" readonly disabled>
                                    <button onclick="add_grocery_cart({{$item->id}},'plus')">+</button>
                                </span>
                            </div>
                            <div class="col right_col text-green text-right">
                                {{ $currency }}<input type="text" class="price text-green" id="{{'price' . $item->id}}"
                                    value="{{ $cartData['price'] }}" class="form-control" readonly>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        @else
                        <div class="row grocery_row">
                            <div class="col left_col text-left">
                                <span class="qty">
                                    <button class="minus" onclick="add_grocery_cart({{$item->id}},'minus')">-</button>
                                    <input type="text" value="0" id="{{'qty' . $item->id}}" name="qty" readonly
                                        disabled>
                                    <button onclick="add_grocery_cart({{$item->id}},'plus')">+</button>
                                </span>
                            </div>
                            <div class="col right_col text-green text-right">
                                {{ $currency }}<input type="text" class="price text-green" id="{{'price' . $item->id}}"
                                    value="{{ $item->sell_price }}" class="form-control" readonly>
                            </div>
                        </div>
                        @endif
                        @endif
                        <input type="hidden" value="{{ $item->sell_price }}" id="{{'original_price' . $item['id']}}">
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
