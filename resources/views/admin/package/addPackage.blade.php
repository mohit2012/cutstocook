@extends('admin.master', ['title' => __('Add Package')])

@section('content')
    @include('admin.layout.topHeader', [
        'title' => __('Add Package') ,
        'headerData' => __('Package') ,
        'url' => 'owner/Package' ,
        'class' => 'col-lg-7'
    ]) 
    <div class="container-fluid mt--7">
           
            <div class="row">
                    <div class="col-xl-12 order-xl-1">
                        <div class="card form-card bg-secondary shadow">
                            <div class="card-header bg-white border-0">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h3 class="mb-0">{{ __('Add Package') }}</h3>
                                    </div>
                                    <div class="col-4 text-right">
                                        <a href="{{ url('owner/Package') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="post" action="{{url('owner/Package')}}" autocomplete="off" enctype="multipart/form-data" >
                                    @csrf
                                    
                                    <h6 class="heading-small text-muted mb-4">{{ __('Package Detail') }}</h6>
                                    <div class="pl-lg-4">
                                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                            
                                            <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                            <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required autofocus>
                                            @if ($errors->has('name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('shop_id') ? ' has-danger' : '' }}">
                                            
                                            <label class="form-control-label" for="input-shop_id">{{ __('Shop') }}</label>
                                            <select name="shop_id" id="input-shop_id" class="form-control select2 form-control-alternative{{ $errors->has('shop_id') ? ' is-invalid' : '' }}" required >
                                                <option value="">Select Shop</option>
                                                @foreach ($shops as $data)
                                                <option value="{{$data->id}}"{{$data->id == old('shop_id')?'Selected' : ''}}>{{$data->name}}</option>
                                                @endforeach                                                
                                            </select>
                                            @if ($errors->has('shop_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('shop_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                     
                                        <div class="form-group{{ $errors->has('items') ? ' has-danger' : '' }}">
                                            
                                            <label class="form-control-label" for="input-items">{{ __('Items') }}</label>
                                            <select name="items[]" id="input-items"  multiple="multiple" class="form-control select2  select2-multiple form-control-alternative{{ $errors->has('items') ? ' is-invalid' : '' }}" required data-placeholder='{{ __("Choose Items")}}' >
                                                <option value="">Select Shop</option>
                                                @foreach ($items as $data)
                                                <option value="{{$data->id}}"{{$data->id == old('items')?'Selected' : ''}}>{{$data->name}}</option>
                                                @endforeach                                                
                                            </select>
                                            @if ($errors->has('items'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('items') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('package_price') ? ' has-danger' : '' }}">
                                            
                                            <label class="form-control-label" for="input-package_price">{{ __('Price') }}</label>
                                            <input type="number" name="package_price" min ="0" id="input-package_price" class="form-control form-control-alternative{{ $errors->has('package_price') ? ' is-invalid' : '' }}" placeholder="{{ __('Price') }}" value="{{ old('package_price') }}" required>
                                            @if ($errors->has('package_price'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('package_price') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="form-group{{ $errors->has('image') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="input-image">{{ __('Image') }}</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" accept=".png, .jpg, .jpeg, .svg" name="image" id="image" required>
                                                    <label class="custom-file-label" for="image">Select file</label>
                                                </div>
                                                @if ($errors->has('image'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('image') }}</strong>
                                                    </span>
                                                @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-status">{{ __('Status') }}</label>
                                            <Select name="status" id="input-status" class="form-control form-control-alternative{{ $errors->has('status') ? ' is-invalid' : '' }}"  required>
                                                <option value="">Select Status</option>
                                                <option value="0" {{ old('status')=="0" ? 'Selected' : ''}}>Active</option>
                                                <option value="1" {{ old('status')=="1" ? 'Selected' : ''}}>Inactive</option>
                                            </select>
        
                                            @if ($errors->has('status'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('status') }}</strong>
                                                </span>
                                            @endif
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