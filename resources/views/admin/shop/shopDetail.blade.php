@extends('admin.master', ['title' => __($data->name)])

@section('content')
    @include('admin.layout.topHeader', [
        'title' => __($data->name) ,
        'headerData' => __('Shops') ,
        'url' => 'owner/Shop' ,
        'class' => 'col-lg-7'
    ]) 
    <div class="container-fluid mt--7">
           
        <div class="row">
            <div class="col">
                    <div class="card form-card shadow" style="border:none;" >                       
                        <div class="card-top-header"  style="background-image: url({{url('images/upload/'.$data->image)}});">
                        </div> 
                        <div class="card-body shop-detail">
                            <div class="row">
                                <div class="col-6">
                                    <h2 class="m-0">{{$data->name}}</h2>
                                    <p class="m-0">{{$data->address}}</p>
                                    <p class="m-0">{{ $data->locationData?$data->locationData->name:'-'}}</p>
                                    @if ($data->phone!=null)
                                    <p class="m-0">{{'(p) : +91 '.$data->phone}}</p>
                                    @else 
                                    <p class="m-0">{{'(p) : +91 '.Auth::user()->phone}}</p>
                                    @endif
                                    <p>{{$data->website}}</p>
                                    @if ($data->veg==1)
                                    <p class="b-1"><i class="fas fa-seedling mr-2 text-success"></i> Pure Veg</p>
                                    @else 
                                    <p><i class="fas fa-utensils text-danger mr-2"></i>Non-veg</p>
                                    @endif
                                    <input type="hidden" id="latitude" value="{{$data->latitude}}" name="latitude">
                                    <input type="hidden" id="longitude" value="{{$data->longitude}}" name="longitude">
                                    <input type="hidden" id="shop_name" value="{{$data->name}}" name="shop_name">
                                    <input type="hidden" id="radius" value="{{$data->radius}}" name="radius">
                                    
                                </div>
                                <div class="col-6 text-right">
                                    <div class="rating">
                                        @for ($i =1 ; $i <= 5; $i++)                                        
                                            <i class="fas fa-star {{$i<=$data->rate ? 'active' : ''}}"></i>
                                        @endfor
                                    </div>  
                                    <a href="{{url('owner/shopReview/'.$data->id)}}"><span class="badge rate-label mt-1">{{$data->rate}}({{$data->totalReview}})</span></a>                                 
                                </div>
                               <div class="col-12">
                                    <hr class="mt-2 mb-1">
                               </div>
                               <div class="col-12 row shop-data">
                                   <div class="col-3 br-1 pl-4">
                                        <h4>{{ __('max Delivery Time')}}</h4>
                                        <p>{{$data->delivery_time.' min'}}</p>
                                   </div>
                                   <div class="col-3 br-1 pl-4">
                                        <h4>{{ __('Delivery Charge')}}</h4>
                                        <p>{{$currency.$data->delivery_charge}}</p>
                                    </div>
                                    <div class="col-3 br-1 pl-4">
                                        <h4>{{ __('Shop Charge')}}</h4>
                                        <p>{{$currency.$data->rastaurant_charge}}</p>
                                    </div>
                                    <div class="col-3 pl-4">
                                        <h4>{{ __('Opening Hours')}}</h4>
                                        <p>{{$data->open_time.' - '.$data->close_time}}</p>
                                    </div>
                               </div>
                                <div class="col-12">
                                    <hr class="mt-1 mb-2">
                                </div>
                                <div class="col-12 shop-bottom">
                                    <h3 class="mt-4">{{ __('What People Love here')}}:</h3>
                                    <p> 
                                    @foreach ($data->items as $item)
                                        <a href=""><span>{{$item->name.', '}}</span></a>
                                    @endforeach 
                                    </p>
                                    <h3 class="mt-4 mb-4">{{ __('Address')}}:</h3>
                                    <div id="shop_map" class="mb-5 " style="width: 530px; height: 250px; border-radius:5px; border:1px solid rgba(120, 130, 140, 0.2);"></div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
            </div>
        </div>
       
    </div>

@endsection