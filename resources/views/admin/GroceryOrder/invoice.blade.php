@extends('admin.master', ['title' => __('Invoice')])

@section('content')
    @include('admin.layout.topHeader', [
        'title' => __('Invoice') ,
        'headerData' => __('Orders') ,
        'url' => 'owner/GroceryOrder' ,
        'class' => 'col-lg-7'
    ]) 
    <div class="container-fluid mt--7">
           
        <div class="row">
            <div class="col">
                    <div class="card form-card shadow" id="printarea">
                        <div class="card-header border-0">
                            <div class="row align-items-center">
                                <div class="col-12 text-right" >
                                    <a href="{{url('owner/printGroceryInvoice/'.$data->id.$data->order_no)}}"  target="_blank"><button class="btn btn-primary" type="button">Print</button></a>                                    
                                </div>
                               
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <h3>Shop Detail </h3>
                                    <p class="mb-0">{{$data->shop->name}}</p>
                                    <p class="mb-0">{{$data->shop->address}}</p>                                    
                                </div>
                                <div class="col-6 text-right">
                                    <h2 style="font-size:32px;margin-right:10px;">Invoice</h2>
                                </div>
                                <div class="col-12"><hr></div>
                                <div class="col-6 order-left">                                 
                                    <h3>Invoice to</h3>
                                    <p class="mb-0">{{$data->customer->name}}</p>
                                    <p>{{$data->customer->email}}</p>                                    
                                </div>
                                <div class="col-6 text-right order-rigth">
                                    <h3><span>Order Date : </span>{{$data->created_at->format('Y-m-d')}}</h3>
                                    <h3><span>Order Status : </span>{{$data->order_status}}</h3>
                                    <h3><span>Order Id : </span>{{$data->order_no}}</h3>
                                    <h3><span>Payment Type : </span>{{$data->payment_type}}</h3>
                                    <h3><span>Payment Status : </span>{{$data->payment_status==0?'Pending' : 'Complete'}}</h3>
                                    @if($data->payment_token!=null)
                                    <h3><span>Payment ID : </span>{{$data->payment_token}}</h3>
                                    @endif                                        
                                </div>
                            </div>

                            <div class="item-table table-responsive mt-5">
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light">                                       
                                        <tr>
                                            <th scope="col">{{ __('#') }}</th>
                                            <th scope="col">{{ __('Item') }}</th>
                                            <th scope="col">{{ __('Quantity') }}</th>
                                            <th scope="col">{{ __('Price') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $total = 0; ?>
                                        @foreach ($data->orderItem as $item)                                        
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                               
                                                <td>{{ $item->itemName }}</td>
                                                
                                                <td>{{ $item->quantity}}</td>
                                                <td>{{ $currency.$item->price.'.00'}}</td>                                               
                                            </tr> 
                                            <?php $total = $total + $item->price; ?>
                                        @endforeach                                            
                                    </tbody>
                                </table>
                                
                            </div>
                            <div class="table-bottom mt-5  mb-5">
                                <div class="row">
                                    <div class="col-12 text-right">
                                        <h3><span>Items Total : </span>{{$currency.$total.'.00'}}</h3>                                        
                                        <h3><span>Delivery Charge : </span>{{$currency.$data->delivery_charge.'.00'}}</h3>
                                        <h3><span>Coupon Discount : </span>{{$currency.$data->coupon_price.'.00'}}</h3>
                                        <h3><span>Total Payment : </span>{{$currency}}{{$total + $data->delivery_charge - $data->coupon_price}}.00</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
       
    </div>

@endsection