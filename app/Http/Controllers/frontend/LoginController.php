<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\controller;
use App\CompanySetting;
use App\Notifications\ResetPassword;
use App\Setting;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Faker\Provider\ar_JO\Company;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function user_login(Request $request)
    {
        $request->validate([
            'email' => ['bail', 'required', 'email'],
            'password' => ['required', 'min:6'],
        ]);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response(['success' => true, 'data' => auth()->user()]);
        } else {
            return response(['success' => false, 'message' => 'this credential does not match our record..!!']);
        }
    }

    public function user_register(Request $request)
    {
        $request->validate([
            'name' => ['bail', 'required'],
            'email' => ['required', 'email','unique:users'],
            'password' => ['required','min:8'],
            'phone' => ['bail','numeric','digits:10'],
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'referral_code' => mt_rand(1000000, 9999999),
            'otp' => mt_rand(100000, 999999),
            'image' => 'user.png',
            'verify' => 1,
            'role' => 1,
            'cover_image' => 'NoPath - Copy (89).png',
        ]);
        return response(['success'=>true]);
    }
    public function forgot_password()
    {
        return view('frontend.forgot_password');
    }

    public function user_forgot_password(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user == null)
        {
            return redirect()->back()->with('message','your email id not found..!!');
        }
        else
        {
            $password = rand(10000000, 99999999);
            $user->password = Hash::make($password);
            $user->save();
            $user->notify(new ResetPassword($password));
            return redirect()->back()->with('message','your new password is send in your mail.please check It.!!');
        }
    }

    public function terms_condition()
    {
        $terms_condition = Setting::find(1)->terms_condition;
        return view('frontend.terms_condition',compact('terms_condition'));
    }

    public function how_it_works()
    {
        $how_works = CompanySetting::find(1)->how_it_works;
        return view('frontend.how_it_works',compact('how_works'));
    }

    public function privacy()
    {
        $privacy = Setting::find(1)->privacy_policy;
        return view('frontend.privacy',compact('privacy'));
    }

    public function about_us()
    {
        $about_us = Setting::find(1)->about_us;
        return view('frontend.about_us',compact('about_us'));
    }
}

