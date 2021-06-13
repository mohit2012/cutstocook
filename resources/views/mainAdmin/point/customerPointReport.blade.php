@extends('admin.master', ['title' => __('Customer Points')])

@section('content')
    @include('admin.layout.topHeader', [
        'title' => __('Customer Points') ,
        'class' => 'col-lg-7'
    ]) 
    <div class="container-fluid mt--7">
        
        
        <div class="row">
            <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">  
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">{{ __('Customer Points') }}</h3>
                                </div>                                
                            </div>
                        </div>

                        <div class="table-responsive">                                                         
                            
                                <table class="table data-table align-items-center table-flush" id="reports">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">{{ __('#') }}</th>
                                            <th scope="col">{{ __('Customer Name') }}</th>
                                            <th scope="col">{{ __('Shop Name') }}</th>
                                            <th scope="col">{{ __('Total Spent') }}</th>
                                            <th scope="col">{{ __('Gain Point') }}</th>
                                            <th scope="col">{{ __('Used Point') }}</th>
                                            <th scope="col">{{ __('Remaining Point') }}</th>
                                            <th scope="col">{{ __('View Log') }}</th>                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)                                          
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{$item->userName}}</td>
                                                <td>{{ $item->shopName }}</td>
                                                <td>{{$currency.$item->total_spent }}.00</td>
                                                <td>{{$item->total_point}} </td>
                                                <td>{{$item->use_point}} </td>
                                                <td>{{$item->total_point - $item->use_point}} </td>
                                                <td>
                                                    <a href="{{url('mainAdmin/viewPointLog/'.$item->user_id.'/'.$item->shop_id)}}" class="table-action" data-toggle="tooltip" data-original-title="view Point Log">
                                                    <i class="fas fa-eye"></i> </a>   
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                   
                                </table>
                                                      
                            </div>
                    </div>
            </div>
        </div>
       
    </div>

@endsection