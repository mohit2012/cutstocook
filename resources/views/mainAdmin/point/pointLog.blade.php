@extends('admin.master', ['title' => __('Point Log')])

@section('content')
    @include('admin.layout.topHeader', [
        'title' => __('Point Log') ,
        'headerData' => __('Loyalty Points') ,
        'url' => 'mainAdmin/customer-loyalty-report' ,
        'class' => 'col-lg-7'
    ])
    {{dd("hi hemali")}}
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
                <div class="card card-profile shadow">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 order-lg-2">
                            <div class="card-profile-image">
                                <a href="#">
                                    <img src="{{url('images/upload/'.$user->image)}}" style="height:180px;width:180px;border: 5px solid #fff;" class="rounded-circle view-image">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                        <div class="d-flex justify-content-between">

                        </div>
                    </div>
                    <div class="card-body pt-0 pt-md-4">
                        <div class="row">
                            <div class="col">
                                <div class="card-profile-stats d-flex justify-content-center mt-md-5">
                                    <div>
                                        <span class="heading">{{$user->total_point}}</span>
                                        <span class="description">{{ __('Gain Points') }}</span>
                                    </div>
                                    <div>
                                        <span class="heading">{{$user->use_point}}</span>
                                        <span class="description">{{ __('Used Points') }}</span>
                                    </div>
                                    <div>
                                        <span class="heading">{{$user->total_point - $user->use_point}}</span>
                                        <span class="description">{{ __('Remaining Points') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <h3>{{$user->name}}</h3>
                            <div class="h5 font-weight-300">
                                <i class="ni location_pin mr-2"></i>{{$user->email}}
                            </div>
                            <div class="h5 mt-4">
                                <i class="ni business_briefcase-24 mr-2"></i>{{ __('Total Spent- '.$currency.$user->total_spent) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="col-12 mb-0">{{ __('User Point Log of '.$shop->name) }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <form id="booking-filter" method="post" action="{{url('mainAdmin/pointLogFilter')}}">
                                @csrf
                            <input type="hidden" value="{{$user->id}}" name="user_id" >
                            <input type="hidden" value="{{$shop->id}}" name="shop_id" >
                                <div class="row">
                                    <div class="col-4">
                                        <select class="select2 form-control p-0" name="reportPeriod" id="reportPeriod">
                                            <option value="">Select</option>
                                            <option value="year">Year</option>
                                            <option value="month">Month</option>
                                            <option value="week">Week</option>
                                            <option value="day">Day</option>
                                        </select>
                                    </div>
                                    <div class="col-4 filterPeriod">
                                        <input type="text"  class="form-control" id="period_day"   name="period_day">
                                        <input type="text" style="display:none;" class="form-control" id="period_week"  name="period_week">
                                        <select style="display:none;" class="select2 form-control" id="period_month"  name="period_month">
                                            <option value="01">January</option>
                                            <option value="02">February</option>
                                            <option value="03">March</option>
                                            <option value="04">April</option>
                                            <option value="05">May</option>
                                            <option value="06">June</option>
                                            <option value="07">July</option>
                                            <option value="08">August</option>
                                            <option value="09">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                        <input type="number" style="display:none;" class="form-control" id="period_year"  name="period_year">
                                    </div>
                                    <div class="col-4">
                                        <button class="btn btn-primary" type="submit">Apply</button>
                                    </div>
                                </div>
                            </form>

                            <table class="table data-table align-items-center table-flush" id="reports">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">{{ __('#') }}</th>
                                        <th scope="col">{{ __('Order ID') }}</th>
                                        <th scope="col">{{ __('Gain Point') }}</th>
                                        <th scope="col">{{ __('Redeem Point') }}</th>
                                        <th scope="col">{{ __('Total Spent') }}</th>
                                        <th scope="col">{{ __('Payment Gateway') }}</th>
                                        <th scope="col">{{ __('Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($log as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{$item->order->order_no}}</td>
                                            <td>{{$item->point==null?0:$item->point}} </td>
                                            <td>{{$item->reeem_point==null?0:$item->reeem_point}} </td>
                                            <td>{{ $currency.$item->order->payment }}</td>
                                            <td>{{ $currency.$item->order->payment_type }}</td>
                                            <td>{{$item->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
