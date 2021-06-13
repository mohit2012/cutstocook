@extends('frontend.layouts.app')

@section('title','Forgot Password')
@section('content')
@if (session('message'))
    <div class="alert alert-info forgot_password_alert">
        {{ session('message') }}
    </div>
@endif
<div class="container p-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header w-100 h-100 t1 text-center">Reset Password</div>
                <div class="card-body">
                    <form method="POST" action="{{ url('user_forgot_password') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                            <div class="col-md-6">
                                <input id="email" type="email" name="email" value="" style="text-transform: none;" required="required" autocomplete="email" autofocus="autofocus" class="form-control ">
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn bg-blue text-white">Send Password Reset Link</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


