@extends('frontend.layouts.app')

@section('title','User Information')
@section('content')
<div class="container-fuild settingContainer">
    <img src="{{ url('images/upload/'.$data->cover_image) }}" class="view-image" width="2000px" height="500px" alt="">
    <div class="container user_details_container">
        <div class="row">
            <div class="col-12 col-md-12 col-sm-12 float-right">
                <div class="image-upload">
                    <label for="file-input">
                        <img src="{{ url('frontend/image/icon/Group 5778.png') }}" />
                    </label>
                    <input id="file-input" type="file" />
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <nav class="navbar navbar-expand">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <img src="{{ url('images/upload/'.$data->image) }}" class="rounded-circle image_user" alt="">
            </li>
            <li class="nav-item">
            </li>
            <li class="nav-item t1">
                <div class="font-weight-bold">{{auth()->user()->name}}</div>
            </li>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="nav-link font-weight-bold order_history t1 activeItem">order history</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link font-weight-bold Review t1">Review</a>
            </li>
        </ul>
    </nav>
</div>

<div class="container-fuild show_order_history bg-light p-2">
    <div class="container couponContainer">
        <h4 class="text-left">Food Order</h4>
        <hr class="hr">
        @if (count($order_on_way)>0)
        @foreach ($order_on_way as $order)
        <div class="card m-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 text-left">
                        <input type="hidden" value="{{ $order->shop['id'] }}" id="{{'shop_id' . $order->id }}">
                        <input type="hidden" value="{{ $order->id }}" id="{{'order_id' . $order->id }}">
                        <img src="{{ url('images/upload/'.$order->shop['image']) }}" width="100" height="100"
                            class="rounded-lg" alt="">
                    </div>
                    <div class="col-md-8 text-left">
                        <div class="row">
                            <div class="col-12 col-md-12 col-sm-12">
                                <div class="your_orders">{{ $order->shop['name'] }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-12 col-sm-12">
                                <div class="t1">{{ $order->location?$order->location['name']:'' }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-lg-3 t1">
                                @if ($order->items != null)
                                @foreach ($order->orderItems as $order_item)
                                {{$order_item->quantity}} x
                                {{ $order_item->ItemName }}
                                {{ $order_item->packageName }}<br>
                                @endforeach
                                @endif
                            </div>
                            <div class="col-md-3 col-lg-3">
                                <div class="t1">{{$order->created_at->diffForHumans()}}</div>
                            </div>
                            <div class="col-md-3 col-lg-3">
                                @if ($order->order_status == 'Pending' || $order->order_status == 'Approved')
                                <p class="text-green ">Order is {{ $order->order_status }}</p>
                                @else
                                <p class="text-danger">Order is {{ $order->order_status }}</p>
                                @endif
                            </div>
                            <div class="col-md-3 col-lg-3">
                                @if ($order->order_status == 'Pending')
                                    <a href="{{ url('cancel_order/'.$order->id) }}" class="text-danger">Cancel Order</a><br>
                                @endif
                                @if ($order->order_status != 'Cancel' && $order->order_status != 'Pending')
                                @php
                                $reviews = App\Review::where('customer_id',auth()->user()->id)->where('order_id',$order->id)->get();
                                @endphp
                                @if (count($reviews) == 0)
                                <a href="" class="{{'add_review'.$order->id}} text info" data-toggle="modal"
                                    data-target="#exampleModalCenter{{$order->id}}">Add Review</a>
                                    <div class="display_review{{ $order->id }}">

                                    </div>
                                @else
                                    <small><i>Review: </i></small>
                                    <span>{{$reviews[0]->message}}</span>
                                @endif
                                @endif
                            </div>
                        </div>
                        <div class="modal fade" id="exampleModalCenter{{$order->id}}" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Add Review</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row form-group">
                                            <div class="col-md-4">
                                                <h5> Add your review </h5>
                                            </div>
                                            <div class="col">
                                                <textarea name="message" class="form-control"
                                                    id="{{'message' . $order->id}}"></textarea>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-md-4">
                                                Rate
                                            </div>
                                            <div class="col ratings">
                                                <i class="fa fa-star-o" aria-hidden="true"
                                                    onclick="makeRating('#exampleModalCenter{{$order->id}}', 1)"></i>
                                                <i class="fa fa-star-o" aria-hidden="true"
                                                    onclick="makeRating('#exampleModalCenter{{$order->id}}', 2)"></i>
                                                <i class="fa fa-star-o" aria-hidden="true"
                                                    onclick="makeRating('#exampleModalCenter{{$order->id}}', 3)"></i>
                                                <i class="fa fa-star-o" aria-hidden="true"
                                                    onclick="makeRating('#exampleModalCenter{{$order->id}}', 4)"></i>
                                                <i class="fa fa-star-o" aria-hidden="true"
                                                    onclick="makeRating('#exampleModalCenter{{$order->id}}', 5)"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary"
                                            onclick="add_review('#exampleModalCenter{{$order->id}}',{{ $order->id }})">Add
                                            Review</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="float-right font-weight-normal order_currency order_confirm_text">
                            {{ $currency }}{{ $order->payment }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @else
        <h5 class="text-center">No Orders yet..!!</h5>
        @endif

        <h4 class="text-left">Grocery Order</h4>
        <hr class="hr">
        @if (count($grocery_order)>0)
        @foreach ($grocery_order as $order)
        <div class="card m-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 text-left">
                        <input type="hidden" value="{{ $order->shop['id'] }}" id="{{'shop_id' . $order->id }}">
                        <input type="hidden" value="{{ $order->id }}" id="{{'order_id' . $order->id }}">
                        <img src="{{ url('images/upload/'.$order->shop['image']) }}" width="100" height="100"
                            class="rounded-lg" alt="">
                    </div>
                    <div class="col-md-8 text-left">
                        <div class="row">
                            <div class="col-12 col-md-12 col-sm-12">
                                <div class="font-weight-bold">{{ $order->shop['name'] }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-lg-3 t1">
                                @foreach ($order->orderItems as $order_item)
                                {{$order_item->quantity}} x
                                {{ $order_item->ItemName }}
                                @endforeach
                            </div>
                            <div class="col-md-3 col-lg-3">
                                <div class="t1">{{$order->created_at->diffForHumans()}}</div>
                            </div>
                            <div class="col-md-3 col-lg-3">
                                @if ($order->order_status == 'Pending' || $order->order_status == 'Approved')
                                <p class="text-green ">Order is {{ $order->order_status }}</p>
                                @else
                                <p class="text-danger">Order is {{ $order->order_status }}</p>
                                @endif
                            </div>
                            <div class="col-md-3 col-lg-3">
                                @if ($order->order_status == 'Pending')
                                    <a href="{{ url('cancel_grocery_order/'.$order->id) }}" class="text-danger">Cancel Order</a><br>
                                @endif
                                @if ($order->order_status != 'Cancel' && $order->order_status != 'Pending')
                                    @php
                                        $grocery_reviews = App\GroceryReview::where('customer_id',auth()->user()->id)->where('order_id',
                                        $order->id)->get();
                                    @endphp
                                    @if(count($grocery_reviews) <= 0)
                                        <a href="" class="{{'add_review'.$order->id}} text info" data-toggle="modal" data-target="#orderModalCenter{{$order->id}}">Add Review</a>
                                        <div class="display_review{{ $order->id }}">

                                        </div>
                                    @else
                                        <div class="review_display" {{ $order->id }}>
                                            <small><i>Review: </i></small>
                                            <span>{{$grocery_reviews[0]->message}}</span>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                <div class="col-md-2 text-left">
                    <div class="float-right font-weight-normal order_confirm_text">{{ $currency }}{{ $order->payment }}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="orderModalCenter{{$order->id}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Review</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row form-group">
                            <div class="col-md-4">
                                <h5> Add your review </h5>
                            </div>
                            <div class="col">
                                <textarea name="message" class="form-control"
                                    id="{{'message' . $order->id}}"></textarea>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                                Rate
                            </div>
                            <div class="col ratings">
                                <i class="fa fa-star-o" aria-hidden="true"
                                    onclick="makeRating('#orderModalCenter{{$order->id}}', 1)"></i>
                                <i class="fa fa-star-o" aria-hidden="true"
                                    onclick="makeRating('#orderModalCenter{{$order->id}}', 2)"></i>
                                <i class="fa fa-star-o" aria-hidden="true"
                                    onclick="makeRating('#orderModalCenter{{$order->id}}', 3)"></i>
                                <i class="fa fa-star-o" aria-hidden="true"
                                    onclick="makeRating('#orderModalCenter{{$order->id}}', 4)"></i>
                                <i class="fa fa-star-o" aria-hidden="true"
                                    onclick="makeRating('#orderModalCenter{{$order->id}}', 5)"></i>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary"
                            onclick="add_grocery_review('#orderModalCenter{{$order->id}}',{{ $order->id }})">Add
                            Review</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @else
    <h5 class="text-center">No Orders yet..!!</h5>
    @endif

</div>

<div class="container-fuild show_Review">
    <div class="container couponContainer">
        <h4 class="text-left">Food Reviews</h4>
        <hr class="hr">
        @if (count($reviews)>0)
        <div class="row">
            @foreach ($reviews as $review)
            <div class="col-md-6 col-sm-12 p-3">
                <div class="row couponList grocery_review">
                    <div class="col-md-2 col-sm-4 text-center">
                        @php
                        $user_id = $review->customer_id;
                        $user = App\User::find($user_id);
                        @endphp
                        <img src="{{ url('images/upload/'.$user->image) }}" class="rounded-circle" width="50" height="50"
                            alt="">
                    </div>
                    <div class="col-md-5 col-sm-4 grocery_review_customer">
                        <p class="text-left t1">{{ $data->name }}</p>
                        <p class="text-secondary">{{$user->created_at->diffForHumans()}}</p>
                    </div>
                    <div class="col-md-5 col-sm-4 grocery_review_star">
                        @php
                        $star = $review->rate
                        @endphp
                        @for ($i = 1; $i < 6; $i++) @if ($i <=$star) <i class="fa fa-star text-green"
                            aria-hidden="true"></i>
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
            </div>
            @endforeach
        </div>
        @else
            <h5 class="text-center">No Review available..</h5>
        @endif
    </div>

    <div class="container couponContainer">
        <h4 class="text-left">Grocery Reviews</h4>
        <hr class="hr">
        @if (count($grocery_reviews)>0)
        <div class="row">
            @foreach ($grocery_reviews as $review)
            <div class="col-md-6 col-sm-12 p-3">
                <div class="row couponList grocery_review">
                    <div class="col-md-2 col-sm-4 text-center">
                        @php
                        $user_id = $review->customer_id;
                        $user = App\User::find($user_id);
                        @endphp
                        <img src="{{ url('images/upload/'.$user->image) }}" class="rounded-circle" width="50" height="50"
                            alt="">
                    </div>
                    <div class="col-md-5 col-sm-4 grocery_review_customer">
                        <p class="text-left t1">{{ $data->name }}</p>
                        <p class="text-secondary">{{$user->created_at->diffForHumans()}}</p>
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
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-10">
                        <p class="text-left text-secondary">{{ $review->message }}</p>
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <h5 class="text-center">No Review available..</h5>
        </div>
        @endif
    </div>
</div>
@endsection
