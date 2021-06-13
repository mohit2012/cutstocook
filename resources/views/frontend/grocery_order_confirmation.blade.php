@extends('frontend.layouts.app')

@section('title','Order Confirmation')
@section('content')

@if ($errors->any())
<div class="alert alert-danger">
    @foreach ($errors->all() as $item)
        {{ $item }}<br>
    @endforeach
</div>
@endif
<div class="container groceryOrderContainer">
    <input type="hidden" name="" id="hidden_coupen_id" value="{{0}}">
    <input type="hidden" name="" id="hidden_coupen_discount" value="{{0}}">
    <input type="hidden" name="" id="hidden_coupen_discountType" value="{{0}}">
    @php
        $price = 0;
    @endphp
    @foreach (Session::get('GrocarycartData') as $cart)
        @php
        $price += intval($cart['price'])
        @endphp
    @endforeach
    <input type="hidden" name="" id="price" value="{{$price}}">
    <div class="row">
        <div class="col-md-7">
            <div class="t1 text-left order_confirm_text">Order confirmation</div>
            <hr class="hr">
        </div>
        <div class="col-md-3">
            <p class="float-left total_amount_dis t1">Total Amount</p>
        </div>
        <div class="col-md-2">
            <p class="to_pay total_amount float-right">
                @if ($shop->delivery_type == 'Home')
                    {{ $currency }}{{$price }}
                @else
                    {{ $currency }}{{$price + $shop->delivery_charge }}
                @endif
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-sm-12 col-md-12">
            <div class="bg-light p-4">
                <h4 class="text-left">Order from</h4>
                <div class="row">
                    <div class="col-md-2">
                        <img src="{{ url('images/upload/'.$shop->image) }}" class="rounded-lg float-left" width="80"
                            height="80" alt="">
                    </div>
                    <div class="col pt-2">
                        <h5 class="text-left">{{ $shop->name }} </h5>
                        <p class="text-left">{{ $shop->address }}</p>
                    </div>
                </div>
            </div>

            <div class="order_content bg-light">
                <div class="row">
                    <div class="col-6 col-md-8">
                        <div class="bg-light p-2">
                            <h4 class="t1 text-left">your order</h4>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="bg-light p-2">
                            <a href="{{url('remove_cart')}}" class="text-danger text-right">Cancel order</a>
                        </div>
                    </div>
                </div>
                <div class="row cartItem">
                   <div class="col-md-12 col-12 col-sm-12">
                    @foreach (Session::get('GrocarycartData') as $item)
                        <div class="row" style="margin-bottom: 7px;">
                            @if (count($item)>0)
                            <div class="col-6 col-md-8 col-sm-6 col-12">
                                <h5 class="text-left">{{  App\GroceryItem::find($item['id'])->name }}</h5>
                            </div>
                            <div class="col-6 col-md-4 col-sm-6 col-12 text-left pb-3">
                                @if (Session::get('GrocarycartData') == null)
                                    <span class="qty">
                                        <button class="{{'minus'+$item->id}}" onclick="add_grocery_cart({{$item->id}},'minus')">-</button>
                                        <input type="text" value="0" id="{{'qty' . $item->id}}" name="qty" readonly disabled>
                                        <button onclick="add_grocery_cart({{$item->id}},'plus')">+</button>
                                    </span>
                                    {{ $currency }}<input type="text" class="price" id="{{'price' . $item['id']}}"
                                        value="{{ $item->sell_price }}" class="form-control" readonly>
                                @else
                                    @if(in_array($item['id'], array_column(Session::get('GrocarycartData'), 'id')))
                                        @foreach (Session::get('GrocarycartData') as $cartData)
                                            @if($cartData['id'] == $item['id'])
                                            <span class="qty">
                                                <button class="{{'minus'.$item['id']}}" onclick="add_grocery_cart({{$item['id']}},'minus')">-</button>
                                                <input type="text" value="{{$cartData['qty']}}" id="{{'qty' . $item['id']}}" name="qty"
                                                    readonly disabled>
                                                <button onclick="add_grocery_cart({{$item['id']}},'plus')">+</button>
                                            </span>
                                            {{ $currency }}<input type="text" class="price" id="{{'price' . $item['id']}}"
                                                value="{{ $cartData['price'] }}" class="form-control" readonly>
                                            @endif
                                        @endforeach
                                    @else
                                        <span class="qty">
                                            <button class="{{'minus'+$item['id']}}" onclick="add_grocery_cart({{$item['id']}},'minus')">-</button>
                                            <input type="text" value="0" id="{{'qty' . $item['id']}}" name="qty" readonly disabled>
                                            <button onclick="add_grocery_cart({{$item['id']}},'plus')">+</button>
                                        </span>
                                        {{ $currency }}<input type="text" class="price" id="{{'price' . $item['id']}}"
                                            value="{{ $item->sell_price }}" class="form-control" readonly>
                                    @endif
                                @endif
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="apply_coupen">
                        <div class="form-inline">
                            <div class="col-3 col-md-3 col-sm-12 pt-3">
                                <img src="{{ url('frontend/image/icon/discount (3).png') }}" class="float-left" alt="">
                                <p class="float-left">Apply coupen</p>
                            </div>
                            <div class="col-6 col-md-6 col-sm-12">
                                <input type="text" class="form-control float-left coupen_textbox" placeholder="add code here.."  id="coupen" onchange="applycoupen('Grocery');" style="text-transform: none">
                            </div>
                            <div class="col-3 col-md-3 col-sm-12 pt-3 remove_coupen_col">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row pt-5 pb-3">
                <div class="col-md-12">
                    <div class="bg-light p-3">
                        <h4 class="text-left">Address</h4>
                    </div>
                    <div class="bg-light text-left">
                        @php
                            $auth_user = auth()->user()->address_id;
                            $user_address = App\UserAddress::find($auth_user);
                            $user_other_address = App\UserAddress::where('user_id',auth()->user()->id)->get()->first();
                        @endphp
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-6 user_select_address">
                                @if($user_address != null)
                                    <div class="text-secondary user_address_display">
                                        <input type="hidden" name="" id="address_id" value="{{$auth_user}}">
                                        <img src="{{ url('frontend/image/icon/Icon awesome-map-marker-alt.png') }}" class="ml-3" alt="">&nbsp;
                                        {{ $user_address->soc_name }},{{ $user_address->street }},{{ $user_address->city }},{{ $user_address->zipcode }}
                                    </div>
                                @elseif($user_other_address != null)
                                    <div class="text-secondary user_address_display">
                                        <input type="hidden" name="" id="address_id" value="{{$user_other_address->id}}">
                                        <img src="{{ url('frontend/image/icon/Icon awesome-map-marker-alt.png') }}" class="ml-3" alt="">&nbsp;
                                        {{ $user_other_address->soc_name }},{{ $user_other_address->street }},{{ $user_other_address->city }},{{ $user_other_address->zipcode }}
                                    </div>
                                @else
                                    <div class="text-secondary user_address_display">Please Add any address</div>
                                @endif
                            </div>
                            <div class="col-md-6 col-sm-6 col-6">
                                <a href="" class="text-green float-right mr-2" data-toggle="modal" data-target="#exampleModalCenter">Address</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="" id="hidden_delivery_charge" value="{{ $shop->delivery_charge }}">
        <input type="hidden" name="" id="hidden_item_total" value="{{ $price }}">
        <input type="hidden" name="" id="currency" value="{{ $currency }}">
        <div class="col-md-12 h-50 w-100 col-lg-4 totalDIv">
            <div class="bg-light">
                <div class="bill_details text-left">Bill Details</div>
                <table class="table table-borderless text-left">
                    <tr>
                        <td>Item Total</td>
                        <td class="float-right" id="item_total" name="p">{{ $currency }}{{ $price }}</td>
                    </tr>
                    <tr>
                        <td>Delivery charge</td>
                        <td class="float-right delivery_charge">
                            @if ($shop->delivery_type == 'Home')
                                {{ $currency }}00
                            @else
                                {{ $currency }}{{ $shop->delivery_charge }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-green">Total Discount</td>
                        <td id="discount" class="text-green float-right">{{$currency}}0</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td>To pay</td>
                        <td id="to_pay" class="float-right to_pay">
                            @if ($shop->delivery_type == 'Home')
                                {{ $currency }}{{$price }}
                            @else
                                {{ $currency }}{{$price + $shop->delivery_charge }}
                            @endif
                            </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div id="saved"></div>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="radio" name="delivery_type" id="delivery_type_shop" value="delivery" {{ $shop->delivery_type == 'Shop' ? 'disabled' : '' }}  {{ $shop->delivery_type == 'Home' ? 'checked' : '' }} checked>&nbsp;Delivery</td>
                        <td><input type="radio" name="delivery_type" id="delivery_type_home" value="pick up" {{ $shop->delivery_type == 'Home' ? 'disabled' : '' }} {{ $shop->delivery_type == 'Shop' ? 'checked' : '' }}>&nbsp;Pick Up</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="2">
                            <button class="btn btn-lg green text-white" id="select_payment_method">Select payment method</button>
                        </td>
                    </tr>
                </table>
            </div>
            <div class=" payment_method">
                <div class="row pt-5 payment_method_row">
                    <div class="col-xs-12 col-md-12 col-lg-12 col-sm-12 pt-5">
                        <div class="t1 bg-light pt-5">
                            <h4 class="text-center">Select payment method</h4>
                            @if ($shop->delivery_type == 'Home')
                                <input type="hidden" name="total_payment" id="total_payment" value="{{ $price }}">
                            @else
                                <input type="hidden" name="total_payment" id="total_payment" value="{{ $price + $shop->delivery_charge }}">
                            @endif
                            <button class="btn bg-blue text-white text-center m-1" onclick="case_on_delivery()">case on delivery</button>
                            <button class="btn bg-blue text-white text-center m-1" onclick="online_payment()">Online Payment</button>
                        </div>
                    </div>
                </div>
                <div class="row pt-2">
                    <div class="col-xs-12 col-md-12 col-sm-12">
                        <div class="t1 bg-light online_payment">
                            <h4 class="text-center">Set card details</h4>
                            <p class="text-secondary">select service:</p>
                            <div class="text-center payment_type">
                                <div class="text-center payment_type">
                                    <?php
                                        $c = App\Setting::find(1)->currency
                                    ?>
                                    <input type="hidden" name="" id="hidden_cuurency_type" value="{{ $c }}">
                                    @if ($payment_setting->paypal == 1)
                                        <label class="radio" {{$c == 'INR' ? 'disabled' : ''}}>
                                            <input type="radio" value="PAYPAL" checked name="payment" {{$c == 'INR' ? 'disabled' : ''}}>
                                            <img src={{ url('frontend/image/icon/PayPal_logo_logotype_emblem.png')}} alt="">
                                        </label>
                                    @endif

                                    @if ($payment_setting->razor == 1)
                                        <label class="radio">
                                            <input type="radio" {{ $c == 'INR' ? 'checked' : '' }} value="RAZOR" name="payment">
                                            <img src={{ url('frontend/image/icon/razorpay.png') }} alt="">
                                        </label>
                                    @endif

                                    @if ($payment_setting->stripe == 1)
                                        <label class="radio">
                                            <input type="radio" value="STRIPE" name="payment">
                                            <img src={{ url('frontend/image/icon/stripe.png')}} alt="">
                                        </label>
                                    @endif

                                    @if ($payment_setting->flutterwave == 1)
                                        <label class="radio">
                                            <input type="radio" value="FLUTTERWAVE" name="payment">
                                            <img src={{ url('frontend/image/icon/Flutterwave.png')}} width="75px" height="20px" alt="">
                                        </label>
                                    @endif

                                    @if ($payment_setting->paystack == 1)
                                        <label class="radio">
                                            <input type="radio" value="PAYSTACK" name="payment">
                                            <img src={{ url('frontend/image/icon/paystack_logo.png')}} width="75px" height="20px" alt="">
                                        </label>
                                    @endif
                                </div>
                            </div>
                            <div class="payment_gateway">
                                <div class="show" id="paypal">
                                    <div class="card card-body paypal_payment">
                                    </div>
                                </div>
                                <div class="hide-div" id="razorpay">
                                    <div class="card card-body">
                                        <form id="rzp-footer-form" action="{{url('razor')}}" method="POST">
                                            @csrf
                                            <br />
                                            @php
                                            $razor_publish_key = App\PaymentSetting::find(1)->razorPublishKey
                                            @endphp
                                            <input type="hidden" name="RAZORPAY_KEY" id="RAZORPAY_KEY"
                                                value="{{$razor_publish_key}}">
                                            <input type="hidden" name="amount" id="amount"
                                                value="{{$price + $shop->rastaurant_charge + $shop->delivery_charge }}" />
                                            <div class="pay">
                                                <button class="razorpay-payment-button btn bg-blue text-white btn filled small" id="paybtn"
                                                    type="button">Pay
                                                    with Razorpay</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="hide-div" id="stripe">
                                    <div class="card card-body">
                                        <form role="form" action="{{ url('frontendStripePayment') }}" method="post"
                                            class="require-validation customform" data-cc-on-file="false"
                                            data-stripe-publishable-key="{{App\PaymentSetting::find(1)->stripePublicKey}}"
                                            id="stripe-payment-form">
                                            @csrf
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="email" class="email form-control required"
                                                            title="Enter Your Email" name="email" required />
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>Card Information</label>
                                                        <input type="text" class="card-number required form-control"
                                                            title="please input only number." pattern="[0-9]{16}"
                                                            name="card-number" placeholder="1234 1234 1234 1234"
                                                            title="Card Number" required />
                                                        <div class="row" style="margin-top:-2px;">
                                                            <div class="col-lg-6 pr-0">
                                                                <input type="text" class="expiry-date required form-control"
                                                                    name="expiry-date" title="Expiration date"
                                                                    title="please Enter data in MM/YY format."
                                                                    pattern="(0[1-9]|10|11|12)/[0-9]{2}$" placeholder="MM/YY"
                                                                    required />
                                                                <input type="hidden"
                                                                    class="card-expiry-month required form-control"
                                                                    name="card-expiry-month" />
                                                                <input type="hidden"
                                                                    class="card-expiry-year required form-control"
                                                                    name="card-expiry-year" />
                                                            </div>
                                                            <div class="col-lg-6 pl-0">
                                                                <input type="text" class="card-cvc required form-control"
                                                                    title="please input only number." pattern="[0-9]{3}"
                                                                    name="card-cvc" placeholder="CVC" title="CVC" required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label>Name on card</label>
                                                        <input type="text" class="required form-control" name="name"
                                                            title="Name on Card" required />
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group text-center">
                                                        <input type="submit" class="btn btn-primary mt-4 btn-submit"
                                                            value="Pay" />
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="hide-div" id="flutterwave">
                                    <div class="card card-body">
                                        <input type="submit" onclick="flutterwave()" class="btn bg-blue text-white" value="payment with flutterwave" />
                                    </div>
                                </div>

                                <div class="hide-div" id="paystack">
                                    <div class="card card-body">
                                        <form id="paymentForm">
                                            <input type="hidden" id="paystack-public-key" value="{{ App\PaymentSetting::find(1)->paystack_public_key }}">
                                            <input type="hidden" id="email-address" value="{{ auth()->user()->email }}" required />
                                            <div class="form-submit">
                                                <button type="button" class="btn bg-blue text-white" onclick="payWithPaystack()">Pay with paystack</button>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="t1 bg-light p-3 text-center offline">
                            <button class="btn green text-white" value="confirm order" onclick="confirm_order()"><i class="fa fa-check"></i>confirm order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Address</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="t1">Add new address</div> <br>
                            <input type="radio" name="address_type" value="home" id="address_type" checked>Home
                            <input type="radio" name="address_type" value="office" id="address_type">Office
                            <input type="text" name="text" id="soc_name" class="form-control mt-2" placeholder="sociaty name..">
                            <p class="soc_name text-danger" role="alert"></p>
                            <input type="text" name="text" id="street" class="form-control mt-2" placeholder="street..">
                            <p class="street text-danger" role="alert"></p>
                            <input type="text" name="text" id="city" class="form-control mt-2" placeholder="city">
                            <p class="city text-danger" role="alert"></p>
                            <input type="text" name="text" id="zipcode" class="form-control mt-2" placeholder="zip code">
                            <p class="zipcode text-danger" role="alert"></p>
                            <input type="checkbox" id="set_as_default" class="mt-2" value="1">Set as default
                            <input type="hidden" name="lat" id="lat" value="22.3039">
                            <input type="hidden" name="lang" id="lang" value="70.8022">
                            <div id="address_map" class="mb-5 form-map"></div>
                        </div>
                        <div class="col-md-6 display_user_address">
                            <h4>User address</h4>
                            <hr>
                            @php
                                $auth_user_id = auth()->user()->address_id;
                            @endphp
                            @foreach ($data as $item)

                                <input type="radio" id="user_select_address" value="{{ $item->id }}" name="user_select_address" {{ $auth_user_id == $item->id ? 'checked' : '' }}>
                                <label for="user_select_address"><img src="{{ url('image/icon/Icon awesome-map-marker-alt.png') }}"
                                    alt="">{{ $item->soc_name }},{{ $item->street }},{{ $item->city }},{{ $item->zipcode }}</label>
                            <hr>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn text-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-t1" onclick="user_address();">User this address</button>
                </div>
            </div>
        </div>
    </div>
@endsection
