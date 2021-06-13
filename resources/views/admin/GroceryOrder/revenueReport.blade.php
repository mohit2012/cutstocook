@extends('admin.master', ['title' => __('Orders')])

@section('content')
    @include('admin.layout.topHeader', [
        'title' => __('Grocery Orders') ,
        'class' => 'col-lg-7'
    ]) 
    <div class="container-fluid mt--7">
           
        <div class="row">
            <div class="col">
                    <div class="card form-card shadow">
                        <div class="card-header border-0">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">{{ __('Grocery Orders') }}</h3>
                                </div>                               
                            </div>
                        </div>

                        <div class="table-responsive">
                               
                                <?php    $total_payment = 0;  ?>                                                        

                                <table class="table data-table align-items-center table-flush" id="reports">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">{{ __('#') }}</th>
                                            <th scope="col">{{ __('Order ID') }}</th>
                                            <th scope="col">{{ __('Customer') }}</th>
                                            <th scope="col">{{ __('Shop Name') }}</th>
                                            <th scope="col">{{ __('Date') }}</th>                                           
                                            <th scope="col">{{ __('Payment Gateway') }}</th>
                                            <th scope="col">{{ __('Order Status') }}</th>
                                            <th scope="col">{{ __('Payment') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                           
                                            <?php $total_payment = $total_payment + $item->payment; ?>
                                           
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{$item->order_no}}</td>
                                                <td>{{ $item->customer->name }}</td>
                                                <td>{{ $item->shop_name }}</td>
                                                <td>{{$item->date}} </td>                                               
                                                <td>{{$item->payment_type}} </td>
                                                <td>{{$item->order_status}} </td>
                                                <td>{{ $currency.$item->payment }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <thead class="footer-head">
                                        <tr >
                                            <th></th> 
                                            <th></th>
                                            <th></th>   
                                            <th></th>                                        
                                            <th></th>   
                                            <th></th>
                                            <th>Total Income </th>
                                            <th>{{$currency.$total_payment}}</th>
                                           
                                        </tr>
                                    </thead>
                                </table>
                                                      
                            </div>
                    </div>
            </div>
        </div>
       
    </div>

@endsection