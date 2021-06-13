@extends('admin.master', ['title' => __('Users')])

@section('content')
    @include('admin.layout.topHeader', [
        'title' => __('Users') ,
        'class' => 'col-lg-7'
    ]) 
    <div class="container-fluid mt--7">
           
        <div class="row">
            <div class="col">
                    <div class="card form-card shadow">
                        <div class="card-header border-0">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">{{ __('Users') }}</h3>
                                </div>
                                {{-- <div class="col-4 text-right">
                                    <a href="{{url('Customer/create')}}" class="btn btn-sm btn-primary">{{ __('Add New user') }}</a>
                                </div> --}}
                            </div>
                        </div>

                        <div class="table-responsive">
                           {{-- {{dd($filterData)}} --}}
                            <form id="booking-filter" method="post" action="{{url('mainAdmin/usersFilter')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-3">
                                        <select class="select2 form-control p-0" name="role" id="role">
                                            <option value="">Select Role</option>  
                                            <option {{isset($filterData)==true && $filterData['role']=="1"?'selected':'' }} value="1">Shop Owners</option>                                                    
                                            <option {{isset($filterData)==true && $filterData['role']=="0"?'selected':'' }} value="0">Customers</option>  
                                            <option {{isset($filterData)==true && $filterData['role']=="2"?'selected':'' }} value="2">Drivers</option>  
                                        </select> 
                                    </div>
                                    <div class="col-3">
                                        <select class="select2 form-control p-0" name="reportPeriod" id="reportPeriod">
                                            <option value="">Select</option>                                                    
                                            <option {{isset($filterData)==true && $filterData['reportPeriod']=="year"?'selected':'' }} value="year">Year</option>
                                            <option {{isset($filterData)==true && $filterData['reportPeriod']=="month"?'selected':'' }} value="month">Month</option>
                                            <option {{isset($filterData)==true && $filterData['reportPeriod']=="week"?'selected':'' }} value="week">Week</option>
                                            <option {{isset($filterData)==true && $filterData['reportPeriod']=="day"?'selected':'' }} value="day">Day</option>
                                        </select> 
                                    </div>
                                    <div class="col-3 filterPeriod">
                                        <input type="text" style="{{isset($filterData)==true && $filterData['reportPeriod']!="day"?'display:none;':''}}"  class="form-control" id="period_day" value="{{isset($filterData)==true?$filterData['period_day']:'' }}"  name="period_day"> 
                                        <input type="text" style="{{isset($filterData)==true && $filterData['reportPeriod']=="week"?'':'display:none;'}}" class="form-control" id="period_week" value="{{isset($filterData)==true?$filterData['period_week']:'' }}" name="period_week"> 
                                        <select style="{{isset($filterData)==true && $filterData['reportPeriod']=="month"?'':'display:none !important;'}}" class="form-control" id="period_month"  name="period_month"> 
                                            <option value="">Select Month</option>
                                            <option {{isset($filterData)==true && $filterData['period_month']=="01"?'selected':'' }} value="01">January</option>
                                            <option {{isset($filterData)==true && $filterData['period_month']=="02"?'selected':'' }} value="02">February</option>
                                            <option {{isset($filterData)==true && $filterData['period_month']=="03"?'selected':'' }} value="03">March</option>
                                            <option {{isset($filterData)==true && $filterData['period_month']=="04"?'selected':'' }} value="04">April</option>
                                            <option {{isset($filterData)==true && $filterData['period_month']=="05"?'selected':'' }} value="05">May</option>
                                            <option {{isset($filterData)==true && $filterData['period_month']=="06"?'selected':'' }} value="06">June</option>
                                            <option {{isset($filterData)==true && $filterData['period_month']=="07"?'selected':'' }} value="07">July</option>
                                            <option {{isset($filterData)==true && $filterData['period_month']=="08"?'selected':'' }} value="08">August</option>
                                            <option {{isset($filterData)==true && $filterData['period_month']=="09"?'selected':'' }} value="09">September</option>
                                            <option {{isset($filterData)==true && $filterData['period_month']=="10"?'selected':'' }} value="10">October</option>
                                            <option {{isset($filterData)==true && $filterData['period_month']=="11"?'selected':'' }} value="11">November</option>
                                            <option {{isset($filterData)==true && $filterData['period_month']=="12"?'selected':'' }} value="12">December</option>
                                        </select>
                                        <input type="number" style="{{isset($filterData)==true && $filterData['reportPeriod']=="year"?'':'display:none;'}}" class="form-control" id="period_year" value="{{isset($filterData)==true?$filterData['period_year']:'' }}"  name="period_year"> 
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-primary" type="submit">Apply</button>
                                    </div>
                                    {{-- onclick="getfilter();" --}}
                                </div>
                            </form>                               
                                <table class="table data-table align-items-center table-flush" id="reports">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">{{ __('#') }}</th>
                                            <th scope="col">{{ __('Image') }}</th>
                                            <th scope="col">{{ __('Name') }}</th>
                                            <th scope="col">{{ __('Email') }}</th>
                                            <th scope="col">{{ __('Phone') }}</th>
                                            {{-- <th scope="col">{{ __('Date of Birth') }}</th> --}}
                                            <th scope="col">{{ __('Status') }}</th>
                                            <th scope="col">{{ __('Role') }}</th> 
                                            <th scope="col">{{ __('Registered at') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><img class="avatar avatar-lg" src="{{url('images/upload/'.$user->image)}}"></td>
                                                <td>{{ $user->name }}</td>
                                                <td>
                                                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                                </td>
                                                <td>{{ $user->phone }}</td>
                                                {{-- <td>{{ $user->dateOfBirth }}</td> --}}
                                                <td>
                                                    <span class="badge badge-dot mr-4">
                                                        <i class="{{$user->status==0?'bg-success': 'bg-danger'}}"></i>
                                                        <span class="status">{{$user->status==0?'Active': 'Block'}}</span>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge border-1">
                                                       @if($user->role==0)
                                                       {{'Customer'}}
                                                       @elseif($user->role==1)
                                                       {{'Shop Owner'}}
                                                       @else 
                                                        {{'Delivery Boy'}}
                                                       @endif
                                                    </span>                                                    
                                                </td>
                                               
                                                {{-- <td>{{$user->created_at->diffForHumans()}}</td> --}}
                                                <td>{{$user->created_at->format('Y-m-d')}}</td> 
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