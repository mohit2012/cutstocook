@extends('admin.master', ['title' => __('Settings')])

@section('content')
    @include('admin.layout.topHeader', [
        'title' => __('Settings') ,
        'class' => 'col-lg-7'
    ])
    <div class="container-fluid mt--7">

        <div class="row">
            <div class="col">
                    <div class="card bg-secondary form-card shadow">
                        <div class="card-header border-0">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">{{ __('Settings') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <ul class="nav nav-tabs tabs-left sideways">
                                        <li><a href="#notification-v" class="active" data-toggle="tab"><i class="fas fa-bell mr-2"></i>{{ __("Notification Setting") }}</a></li>
                                        {{-- <li><a href="#sound-v" data-toggle="tab"><i class="fas fa-volume-up mr-2"></i>{{ __("Sound & vibration") }}</a></li> --}}
                                        <li><a href="#setting-v" data-toggle="tab"><i class="ni ni-settings mr-2"></i>{{ __("Additional setting") }}</a></li>
                                    </ul>
                                </div>
                                <div class="col-9">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="notification-v">
                                            <form method="post" action="{{url('owner/OwnerSetting/'.$data->id)}}" autocomplete="off" enctype="multipart/form-data" >
                                                @csrf
                                                @method('put')
                                                <h6 class="heading-small text-muted mb-4">{{ __("Notification Setting") }}</h6>
                                                <div>
                                                    <div class="form-group{{ $errors->has('web_notification') ? ' has-danger' : '' }}">
                                                        <div class="row">
                                                            <div class="col-5"> <label class="form-control-label">{{ __('Desktop notification when order arrive') }}:</label></div>
                                                            <div class="col-7">
                                                                <label class="custom-toggle">
                                                                    <input type="checkbox" value="1" {{$data->web_notification == 1?'checked':''}} name="web_notification" id="web_notification">
                                                                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="text-right">
                                                        <button type="submit" class="btn btn-primary mt-4">{{ __('Save') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="tab-pane " id="setting-v">
                                            <form method="post" action="{{url('owner/saveOwnerSetting')}}" autocomplete="off" enctype="multipart/form-data" >
                                                @csrf

                                                <h6 class="heading-small text-muted mb-4">{{ __("Additional Setting") }}</h6>
                                                <div>

                                                    <div class="form-group row {{ $errors->has('default_food_order_status') ? ' has-danger' : '' }}">
                                                        <div class="col-3">
                                                            <label class="form-control-label" for="input-default_food_order_status">{{ __('Default Order Status for Food') }}:</label>
                                                        </div>
                                                        <div class="col-9">
                                                            <select name="default_food_order_status" id="input-default_food_order_status" class="form-control form-control-alternative{{ $errors->has('default_food_order_status') ? ' is-invalid' : '' }}" required>
                                                                <option value="">Select Default order Status</option>
                                                                <option value="Pending" {{$data->default_food_order_status=="Pending"?'Selected' : ''}}>Pending</option>
                                                                <option value="Approved" {{$data->default_food_order_status=="Approved"?'Selected' : ''}}>Approved</option>
                                                            </select>
                                                            @if ($errors->has('default_food_order_status'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('default_food_order_status') }}</strong>
                                                            </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="form-group row {{ $errors->has('default_grocery_order_status') ? ' has-danger' : '' }}">
                                                        <div class="col-3">
                                                            <label class="form-control-label" for="input-default_grocery_order_status">{{ __('Default Order Status for Grocery') }}:</label>
                                                        </div>
                                                        <div class="col-9">
                                                            <select name="default_grocery_order_status" id="input-default_grocery_order_status" class="form-control form-control-alternative{{ $errors->has('default_grocery_order_status') ? ' is-invalid' : '' }}" required>
                                                                <option value="">Select Default order Status</option>
                                                                <option value="Pending" {{$data->default_grocery_order_status=="Pending"?'Selected' : ''}}>Pending</option>
                                                                <option value="Approved" {{$data->default_grocery_order_status=="Approved"?'Selected' : ''}}>Approved</option>
                                                            </select>
                                                            @if ($errors->has('default_grocery_order_status'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('default_grocery_order_status') }}</strong>
                                                            </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="text-right">
                                                        <button type="submit" class="btn btn-primary mt-4">{{ __('Save') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>

    </div>

@endsection
