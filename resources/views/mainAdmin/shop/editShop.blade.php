@extends('admin.master', ['title' => __('Edit Shop')])

@section('content')
    @include('admin.layout.topHeader', [
        'title' => __('Edit Shop') ,
        'headerData' => __('Shops') ,
        'url' => 'mainAdmin/AdminShop' ,
        'class' => 'col-lg-7'
    ]) 
    <div class="container-fluid mt--7">
           
            <div class="row">
                    <div class="col-xl-12 order-xl-1">
                        <div class="card form-card bg-secondary shadow">
                            <div class="card-header bg-white border-0">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h3 class="mb-0">{{ __('Edit Shop') }}</h3>
                                    </div>
                                    <div class="col-4 text-right">
                                        <a href="{{ url('mainAdmin/AdminShop') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="post" action="{{url('mainAdmin/AdminShop/'.$data->id)}}" autocomplete="off" enctype="multipart/form-data" >
                                    @csrf
                                    @method('put')
                                    <h6 class="heading-small text-muted mb-4">{{ __('Edit Shop Detail') }}</h6>
                                    <div class="pl-lg-4">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                                    <label class="form-control-label" for="input-name">{{ __('Shop Name') }}</label>
                                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Shop Name') }}" value="{{ $data->name }}" required autofocus>
                                                    @if ($errors->has('name'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }}">
                                                    <label class="form-control-label" for="input-address">{{ __('Shop Address') }}</label>
                                                    <input type="text" name="address" id="input-address" class="form-control form-control-alternative{{ $errors->has('address') ? ' is-invalid' : '' }}" placeholder="{{ __('Shop Address') }}" value="{{$data->address }}" required >
                                                    @if ($errors->has('address'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('address') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group{{ $errors->has('user_id') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-user_id">{{ __('Shop Owner') }}</label>
                                            <select name="user_id" id="input-user_id" class="form-control form-control-alternative{{ $errors->has('user_id') ? ' is-invalid' : '' }}" required>
                                                <option value="">Select Owner</option>
                                                @foreach ($users as $item)
                                                    <option value="{{$item->id}}" {{$data->user_id==$item->id?'Selected':''}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('user_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('user_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>   
                                        <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="input-description">{{ __('Description') }}</label>
                                                <textarea name="description" id="input-description" class="form-control form-control-alternative{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" required>{{ $data->description }}</textarea>
                                                @if ($errors->has('description'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('description') }}</strong>
                                                    </span>
                                                @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group{{ $errors->has('location') ? ' has-danger' : '' }}">
                                                        <label class="form-control-label" for="input-location">{{ __('Location') }}</label>
                                                        <Select name="location" id="input-location" class="form-control form-control-alternative{{ $errors->has('location') ? ' is-invalid' : '' }}"  required>
                                                            <option value="">Select Location</option>
                                                            @foreach ($location as $item)
                                                                <option value="{{$item->id}}" {{ $data->location==$item->id ? 'Selected' : ''}}>{{$item->name}}</option>    
                                                            @endforeach
                                                        </select>
                    
                                                        @if ($errors->has('location'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('location') }}</strong>
                                                            </span>
                                                        @endif
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group{{ $errors->has('pincode') ? ' has-danger' : '' }}">
                                                        <label class="form-control-label" for="input-pincode">{{ __('Pincode') }}</label>
                                                        <input type="text" name="pincode" id="input-pincode" class="form-control form-control-alternative{{ $errors->has('pincode') ? ' is-invalid' : '' }}" placeholder="{{ __('Pincode') }}" value="{{ $data->pincode }}" required >
                                                        @if ($errors->has('pincode'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('pincode') }}</strong>
                                                            </span>
                                                        @endif
                                                </div>
                                            </div>
                                        </div>
                                       
                                        
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group{{ $errors->has('latitude') ? ' has-danger' : '' }}">
                                                    <label class="form-control-label" for="input-latitude">{{ __('Latitude') }}</label>
                                                    <input type="text" name="latitude" id="input-latitude" class="form-control form-control-alternative{{ $errors->has('latitude') ? ' is-invalid' : '' }}" placeholder="{{ __('Latitude') }}" value="{{ $data->latitude }}" required >
                                                    @if ($errors->has('latitude'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('latitude') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group{{ $errors->has('longitude') ? ' has-danger' : '' }}">
                                                    <label class="form-control-label" for="input-longitude">{{ __('Longitude') }}</label>
                                                    <input type="text" name="longitude" id="input-longitude" class="form-control form-control-alternative{{ $errors->has('longitude') ? ' is-invalid' : '' }}" placeholder="{{ __('Longitude') }}" value="{{ $data->longitude }}" required >
                                                    @if ($errors->has('longitude'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('longitude') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                          
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group{{ $errors->has('delivery_time') ? ' has-danger' : '' }}">
                                                    <label class="form-control-label" for="input-delivery_time">{{ __('Approx Delivery Time (Minutes)') }}</label>
                                                    <input type="text" name="delivery_time" id="input-delivery_time" class="form-control form-control-alternative{{ $errors->has('delivery_time') ? ' is-invalid' : '' }}" placeholder="{{ __('Approx Delivery Time') }}" value="{{ $data->delivery_time }}" required >
                                                    @if ($errors->has('delivery_time'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('delivery_time') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group{{ $errors->has('licence_code') ? ' has-danger' : '' }}">
                                                    <label class="form-control-label" for="input-licence_code">{{ __('Certificate/License Code') }}</label>
                                                    <input type="text" name="licence_code" id="input-licence_code" class="form-control form-control-alternative{{ $errors->has('licence_code') ? ' is-invalid' : '' }}" placeholder="{{ __('Certificate/License Code') }}" value="{{ $data->licence_code }}" required >
                                                    @if ($errors->has('licence_code'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('licence_code') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group{{ $errors->has('rastaurant_charge') ? ' has-danger' : '' }}">
                                                    <label class="form-control-label" for="input-rastaurant_charge">{{ __('Shop Charge (Packing/Extra)') }}</label>
                                                    <input type="number" name="rastaurant_charge" id="input-rastaurant_charge" class="form-control form-control-alternative{{ $errors->has('rastaurant_charge') ? ' is-invalid' : '' }}" placeholder="{{ __('Shop Charge') }}" value="{{ $data->rastaurant_charge }}" required >
                                                    @if ($errors->has('rastaurant_charge'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('rastaurant_charge') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group{{ $errors->has('delivery_charge') ? ' has-danger' : '' }}">
                                                    <label class="form-control-label" for="input-delivery_charge">{{ __('Delivery Charge') }}</label>
                                                    <input type="number" name="delivery_charge" id="input-delivery_charge" class="form-control form-control-alternative{{ $errors->has('delivery_charge') ? ' is-invalid' : '' }}" placeholder="{{ __('Delivery Charge') }}" value="{{ $data->delivery_charge }}" required >
                                                    @if ($errors->has('delivery_charge'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('delivery_charge') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group{{ $errors->has('cancle_charge') ? ' has-danger' : '' }}">
                                                    <label class="form-control-label" for="input-cancle_charge">{{ __('Cancel Charge') }}</label>
                                                    <input type="number" name="cancle_charge" id="input-cancle_charge" class="form-control form-control-alternative{{ $errors->has('cancle_charge') ? ' is-invalid' : '' }}" placeholder="{{ __('Cancle Charge') }}" value="{{ $data->cancle_charge }}" required>
                                                    @if ($errors->has('cancle_charge'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('cancle_charge') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                            
                                                <div class="form-group{{ $errors->has('image') ? ' has-danger' : '' }}">
                                                        <label class="form-control-label" for="input-image">{{ __('Image') }}</label>
                                                        <div class="custom-file">
                                                            <input type="file" accept=".png, .jpg, .jpeg, .svg" class="custom-file-input" name="image" id="image">
                                                            <label class="custom-file-label" for="image">Select file</label>
                                                        </div>
                                                        @if ($errors->has('image'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('image') }}</strong>
                                                            </span>
                                                        @endif
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                                                    <label class="form-control-label" for="input-status">{{ __('Status') }}</label>
                                                    <Select name="status" id="input-status" class="form-control form-control-alternative{{ $errors->has('status') ? ' is-invalid' : '' }}"  required>
                                                        <option value="">Select Status</option>
                                                        <option value="0" {{ $data->status=="0" ? 'Selected' : ''}}>Active</option>
                                                        <option value="1" {{ $data->status=="1" ? 'Selected' : ''}}>Inactive</option>
                                                    </select>
                
                                                    @if ($errors->has('status'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('status') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        

                                        <div class="form-group{{ $errors->has('veg') ? ' has-danger' : '' }}">                                            
                                            <div class="row">
                                                <div class="col-2"> <label class="form-control-label">{{ __('Is Pure Veg') }}?</label></div>
                                                <div class="col-10">
                                                    <label class="custom-toggle">
                                                        <input type="checkbox" name="veg" value="1" id="veg"  {{ $data->veg==1 ? 'Checked' : ''}}>
                                                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group{{ $errors->has('featured') ? ' has-danger' : '' }}">                                            
                                            <div class="row">
                                                <div class="col-2"> <label class="form-control-label">{{ __('Is Featured') }}?</label></div>
                                                <div class="col-10">
                                                    <label class="custom-toggle">
                                                        <input type="checkbox" value="1" name="featured" id="featured" {{ $data->featured==1 ? 'Checked' : ''}}>
                                                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group{{ $errors->has('exclusive') ? ' has-danger' : '' }}">                                            
                                            <div class="row">
                                                <div class="col-2"> <label class="form-control-label">{{ __('Is Exclusive') }}?</label></div>
                                                <div class="col-10">
                                                    <label class="custom-toggle">
                                                        <input type="checkbox" value="1" name="exclusive" id="exclusive" {{ $data->exclusive==1 ? 'Checked' : ''}}>
                                                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    
                                   
        
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
       
    </div>

@endsection