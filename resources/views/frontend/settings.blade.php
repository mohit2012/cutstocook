@extends('frontend.layouts.app')

@section('content')
<div class="container-fuild bg-light pb-4 settingContainer">
    <img src="{{ url('images/upload/'.$user->cover_image) }}" class="view-image" width="100%" height="500px" alt="">
    <div class="container couponContainer pt-3">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <h4 class="text-left">Settings</h4>
                <hr class="hr">
                <ul class="list-group">
                    <li class="list-group-item edit_profile_item SettingItemColor text-dark border">Edit Profile</li>
                    <li class="list-group-item notification_item">Notifications</li>
                    <li class="list-group-item location_item">Location</li>
                </ul>
            </div>
            <div class="col-md-8 col-sm-12">
                <div class="edit_profile">
                    <h5 class="text-left">Edit Profile</h5>
                    <hr class="hr">
                    <div class="card-body">
                        <form action="{{ url('update_profile') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row form-group">
                                <div class="col-md-6 col-sm-12">
                                    <h6 class="t1 text-left">Full name</h6>
                                    <input type="text" name="name" id="name" class="form-control bg-light"
                                        value="{{ $user->name }}">
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <h6 class="t1 text-left">Phone number</h6>
                                    <input id="phone" type="text"
                                        class="form-control @error('phone') is-invalid @enderror" name="phone" required
                                        placeholder="Phone number" value="{{ $user->phone }}">
                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6 col-sm-12">
                                    <h6 class="t1 text-left">Profile</h6>
                                    <img src="{{ url('images/upload/'.$user->image) }}" width="50px" class="float-left"
                                        name="image" height="50px" alt="">
                                    <input type="file" name="image" id="image">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-12 col-12 col-sm-12">
                                    <input type="submit" value="change this" onclick="update_profile()"
                                        class="btn bg-blue text-white mt-3 float-left">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="notification">
                    <h4 class="text-left">Notifications</h4>
                    <hr class="hr">
                    <div class="card-body bg-white">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="t1 text-left">Notification on/off</h6>
                                <p class="text-secondary">you can change your notification setting at any time</p>
                            </div>
                            <div class="col-md-6">
                                <label class="switch float-right">
                                    <input type="checkbox" name="notification" class="switch"
                                        {{ App\User::find(auth()->user()->id)->enable_notification == 1 ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="location p-3">
                    <div class="model-body">
                        <div class="row">
                            <div class="col-md-6 edit_user_address">
                                <div class="t1">Address</div><br>
                            </div>
                            <div class="col-md-6 display_user_address">
                                <h4 class="text-left">User address</h4>
                                <hr class="hr">
                                @if(count($data) > 0)
                                @foreach ($data as $item)
                                <div id="{{'address_id' .  $item->id }}" class="text-left">
                                    <i class="fa fa-map-marker web-icon"></i>
                                    {{ $item->soc_name }},{{ $item->street }},{{ $item->city }},{{ $item->zipcode }}
                                    <br>
                                    <img src="{{ url('image/icon/edit_address.png') }}"
                                        onclick="edit_address({{ $item->id }})" class="edit_address" alt="">
                                    <img src="{{ url('image/icon/delete.png') }}"
                                        onclick="delete_address({{ $item->id }})" class="delete_address float-right"
                                        alt="">
                                    <hr>
                                </div>
                                @endforeach
                                @else
                                <p>No address available..!!</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
