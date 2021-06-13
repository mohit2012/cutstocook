@extends('admin.master', ['title' => __('DashBoard')])

@section('content')


    @if(Session::has('success'))
    @include('toast',Session::get('success'))
    @endif

     <div class="header pb-8 pt-5 d-flex pt-lg-8" style="background-image: url({{url('admin/images/profile-cover2.jpg')}}); background-size: cover; background-position: center center;">
        <span class="mask bg-gradient-default opacity-8"></span>
            <div class="container-fluid">            

                <div class="header-body">
                    <!-- Card stats -->
                    <div class="row">
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted">{{ __('Shops')}}</h5>
                                            <span class="h2 font-weight-bold">{{$master['shops']}}</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                                <i class="fas fa-store"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted">{{ __('New users')}}</h5>
                                            <span class="h2 font-weight-bold">{{$master['users']}}</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                                    <i class="fas fa-users"></i>
                                                
                                            </div>
                                        </div>
                                    </div>
                                  
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted">{{ __('Sales')}}</h5>
                                            <span class="h2 font-weight-bold">{{$currency.$master['sales']}}</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                                <i class="fas fa-chart-pie"></i>
                                            </div>
                                        </div>
                                    </div>
                                  
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted">{{ __('Delivery Guys')}}</h5>
                                            <span class="h2 font-weight-bold">{{$master['delivery']}}</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                                 <i class="ni ni-delivery-fast"></i>
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>



    <div class="container-fluid mt--7">
        <?php 
        $sell_product =  \App\Setting::find(1)->sell_product;       
        ?>
        @if($sell_product == 1 || $sell_product == 0)   
        @if(\App\OwnerSetting::where('user_id',Auth::user()->id)->first()->default_food_order_status == "Pending")
        <div class="row">
            <div class="col-12">                
                <div class="card shadow mb-4">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Food Order Requests') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{url('owner/ViewOrder')}}" class="btn btn-sm btn-primary">{{ __('See all') }}</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive" id="pending-order">
                    @if(count($orders)>0) 
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Order ID') }}</th>
                                    <th scope="col">{{ __('Shop') }}</th>   
                                    <th scope="col">{{ __('Customer') }}</th>                                                 
                                    <th scope="col">{{ __('payment') }}</th>    
                                    <th scope="col">{{ __('date') }}</th>    
                                    <th scope="col">{{ __('Payment GateWay') }}</th>    
                                    
                                    <th scope="col">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)                                
                                <tr>
                                    <td><span class="badge label label-light-warning">{{ $order->order_no }}</span></td>
                                    <td>{{ $order->shop->name }}</td>                                                                                                            
                                    <td>{{ $order->customer->name }}</td>                                                                                                            
                                    <td>{{ $currency.$order->payment.'.00'}}</td>
                                    <td>{{ $order->date ." | ".$order->time}}</td>
                                    <td>{{ $order->payment_type}}</td>                                                                                                          
                                    <td>                                           
                                        <a href="{{url('owner/accept-order/'.$order->id)}}" class="table-action" data-toggle="tooltip" data-original-title="Accept Order">
                                            <i class="fas fa-check-square text-success"></i>
                                        </a>
                                        <a href="{{url('owner/reject-order/'.$order->id)}}" class="table-action" data-toggle="tooltip" data-original-title="Reject Order">
                                            <i class="fas fa-window-close text-danger"></i>
                                        </a>                                        
                                        <a href="{{url('owner/viewOrder/'.$order->id.$order->order_no)}}" class="table-action" data-toggle="tooltip" data-original-title="View Order">
                                            <i class="fas fa-eye"></i>
                                        </a>                                                                                                                                                              
                                    </td>   
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else 
                        <div class="empty-state text-center pb-3">
                            <img src="{{url('images/empty3.png')}}" style="width:30%;height:200px;">
                            <h2 class="pt-3 mb-0" style="font-size:25px;">Nothing!!</h2>
                            <p style="font-weight:600;">Your Collection list is empty....</p>
                        </div>
                    @endif
                    </div>
                </div>                
        </div>
    </div>
    @endif
    @endif
    @if($sell_product == 2 || $sell_product == 0)
    @if(\App\OwnerSetting::where('user_id',Auth::user()->id)->first()->default_grocery_order_status == "Pending")
    <div class="row">
        <div class="col-12">                
            <div class="card shadow mb-4">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">{{ __('Grocery Order Requests') }}</h3>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{url('owner/GroceryOrder')}}" class="btn btn-sm btn-primary">{{ __('See all') }}</a>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive" id="grocery-pending-order">
                @if(count($groceryOrders)>0) 
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">{{ __('Order ID') }}</th>
                                <th scope="col">{{ __('Shop') }}</th>   
                                <th scope="col">{{ __('Customer') }}</th>                                                 
                                <th scope="col">{{ __('payment') }}</th>    
                                <th scope="col">{{ __('date') }}</th>    
                                <th scope="col">{{ __('Payment GateWay') }}</th>    
                                
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groceryOrders as $order)                                
                            <tr>
                                <td><span class="badge label label-light-warning">{{ $order->order_no }}</span></td>
                                <td>{{ $order->shop->name }}</td>                                                                                                            
                                <td>{{ $order->customer->name }}</td>                                                                                                            
                                <td>{{ $currency.$order->payment.'.00'}}</td>
                                <td>{{ $order->date ." | ".$order->time}}</td>
                                <td>{{ $order->payment_type}}</td>                                                                                                          
                                <td>                                   
                                    <a href="{{url('owner/accept-grocery-order/'.$order->id)}}" class="table-action"
                                        data-toggle="tooltip" data-original-title="Accept Order">
                                        <i class="fas fa-check-square text-success"></i>
                                    </a>
                                    <a href="{{url('owner/reject-grocery-order/'.$order->id)}}" class="table-action"
                                        data-toggle="tooltip" data-original-title="Reject Order">
                                        <i class="fas fa-window-close text-danger"></i>
                                    </a>                                   
                                    <a href="{{url('owner/viewGroceryOrder/'.$order->id.$order->order_no)}}" class="table-action"
                                        data-toggle="tooltip" data-original-title="View Order">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else 
                    <div class="empty-state text-center pb-3">
                        <img src="{{url('images/empty3.png')}}" style="width:30%;height:200px;">
                        <h2 class="pt-3 mb-0" style="font-size:25px;">Nothing!!</h2>
                        <p style="font-weight:600;">Your Collection list is empty....</p>
                    </div>
                @endif
                </div>
            </div>                
        </div>
    </div>
    @endif
    @endif
    <div class="row">
            <div class="col-8">                  

                    <div class="card shadow mb-4">
                        <div class="card-header border-0">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">{{ __('Our Shops') }}</h3>
                                </div>
                                <div class="col-4 text-right">
                                    <a href="{{url('owner/Shop')}}" class="btn btn-sm btn-primary">{{ __('See all') }}</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                        @if(count($shops)>0) 
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">{{ __('Image')}}</th>
                                        <th scope="col">{{ __('Name') }}</th>
                                        <th scope="col">{{ __('Location') }}</th>
                                        <th scope="col">{{ __('Shop is') }}</th>
                                        <th scope="col">{{ __('Shop Order') }}</th>
                                        <th scope="col">{{ __('Status') }}</th> 
                                        <th scope="col"></th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($shops as $shop)
                                    @if($shop->veg==1)
                                    <?php $veg = 'veg'; ?>
                                    @else
                                    <?php $veg = 'non-veg'; ?>
                                    @endif
                                    <tr>
                                            <th scope="row"> {{$loop->iteration}} </th>
                                            <td><img class="avatar-lg round-5" src="{{url('images/upload/'.$shop->image)}}"></td>
                                            <td>{{$shop->name}}</td>                                
                                            <td>{{$shop->locationData?$shop->locationData->name:''}}</td>
                                            <td>
                                                <img src="{{url('images/'.$veg.'.png')}}" class="mr-2" height="25px" width="25px"> {{$veg}}
                                            </td>
                                            <td>{{$shop->totalOrder}}</td>
                                            <td>  
                                                <span class="badge badge-dot mr-4">
                                                    <i class="{{$shop->status==0?'bg-success': 'bg-danger'}}"></i>
                                                    <span class="status">{{$shop->status==0?'Active': 'Inactive'}}</span>
                                                </span>
                                            </td>
                                            <td>                                                                        
                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                        <a class="dropdown-item" href="{{url('owner/Shop/'.$shop->name)}}">{{ __('View') }}</a>
                                                        <a class="dropdown-item" href="{{url('owner/Shop/'.$shop->id.'/edit')}}">{{ __('Edit') }}</a>
                                                        <a class="dropdown-item"  onclick="deleteData('owner/Shop','{{$shop->id}}');" href="#">{{ __('Delete') }}</a>
                                                        {{-- onclick="deleteData('Shop','{{$shop->id}}');" --}}
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else 
                            <div class="empty-state text-center pb-3">
                                <img src="{{url('images/empty3.png')}}" style="width:40%;height:200px;">
                                <h2 class="pt-3 mb-0" style="font-size:25px;">Nothing!!</h2>
                                <p style="font-weight:600;">Your Collection list is empty....</p>
                            </div>
                        @endif
                        </div>

                    </div>                    
            </div>
            <div class="col-4">
                {{-- items --}}
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Our Items') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{url('owner/Item')}}" class="btn btn-sm btn-primary">{{ __('See all') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(count($items)>0) 
                            <ul class="list-group list-group-flush list my--3">
                                @foreach ($items as $item)
                                    @if($item->isVeg==1)
                                        <?php $item_veg = 'veg'; ?>
                                    @else
                                        <?php $item_veg = 'non-veg'; ?>
                                    @endif
                                    @if($loop->iteration <= 6)
                                    <li class="list-group-item px-0">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <img class=" avatar-lg round-5" src="{{url('images/upload/'.$item->image)}}">                                            
                                            </div>
                                            <div class="col ml--2">
                                                <h4 class="mb-0">
                                                    <a href="{{url('owner/Item/'.$item->id)}}">{{$item->name}}</a>
                                                </h4>                                                                                            
                                                <span class="status">{{$currency.$item->price.'.00'}}</span>                                               
                                            </div>
                                            <div class="col-auto">
                                                <img src="{{url('images/'.$item_veg.'.png')}}" class="mr-2" height="20px" width="20px"> 
                                            </div>
                                        </div>
                                    </li> 
                                    @endif
                                @endforeach                            
                            </ul>
                        @else
                            <div class="empty-state text-center pb-3">
                                <img src="{{url('images/empty3.png')}}" style="width:60%;height:200px;">
                                <h2 class="pt-3 mb-0" style="font-size:25px;">Nothing!!</h2>
                                <p style="font-weight:600;">Your Collection list is empty....</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
       
    </div>

@endsection