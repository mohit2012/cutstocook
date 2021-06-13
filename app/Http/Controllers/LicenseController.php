<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Http\Request;
use Redirect;
use Artisan;
use App\Currency;
use App\Shop;
use App\Package;
use App\GroceryItem;
use Auth;
use App\GroceryShop;
use App\Setting;
use Illuminate\Support\Facades\Hash;
use LicenseBoxAPI;

class LicenseController extends Controller
{
    //
    public function activeLicence(){
        return view('activeLicence');
    }


    public function activeNewLicence(Request $request)
    {
        $request->validate([
            'license_code' => 'bail|required',
            'name' => 'bail|required',
        ]);
        $api = new LicenseBoxAPI();
        $result =  $api->activate_license($request->license_code, $request->name);
        if ($result['status'] === true) {
            Artisan::call('up');
            return redirect('mainAdmin/login');
        }
        else
        {
            return Redirect::back()->with('error_msg', $result['message']);
        }
    }

    public function home()
    {
        if(env('DB_DATABASE')=="")
        {
            return view('frontPage');
        }
        else
        {
            $shops = Shop::all();
            $offers = Package::all()->take(5);
            $grocarries = GroceryItem::with(['category', 'subcategory'])->get()->take(5);
            $grocarries_shop = GroceryShop::with(['locationData'])->get()->take(5);
            $currency_code = Setting::find(1)->currency;
            $currency = Currency::where('code', $currency_code)->first()->symbol;
            return view('frontend.index', compact('shops', 'offers', 'grocarries', 'grocarries_shop', 'currency'));
        }
    }

    public function viewMainAdminLogin()
    {
        if(env('DB_DATABASE')=="")
        {
            return view('frontPage');
        }
        else
        {
            return view('mainAdmin.auth.login');
        }
    }

    public function viewAdminLogin(){

        return view('auth.login');
    }

    public function chkAdmin_login(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|email',
            'password' => 'bail|required',
        ]);

        $userdata = array(
            'email' => $request->email,
            'password' => $request->password,
        );

        if (Auth::guard('mainAdmin')->attempt($userdata))
        {
            $api = new LicenseBoxAPI();
            $res = $api->verify_license();
            if ($res['status'] !== true) {
                Setting::find(1)->update(['license_status'=>0]);
                return redirect('mainAdmin/home');
            }
            else{
                Setting::find(1)->update(['license_status'=>1]);
                return redirect('mainAdmin/home');
            }
            return redirect('mainAdmin/home');
        } else {
            return Redirect::back()->with('error_msg', 'Invalid Username or Password');
        }
    }

    public function saveLicenseSettings(Request $request){

        $request->validate([
            'license_key' => 'bail|required',
            'license_name' => 'bail|required',
        ]);
        $api = new LicenseBoxAPI();
        $result =  $api->activate_license($request->license_key, $request->license_name);
        if ($result['status'] === true)
        {
            Setting::find(1)->update(['license_status'=>1,'license_key'=>$request->license_key,'license_name'=>$request->license_name]);
            return redirect('mainAdmin/home');
        }
        else{
            Setting::find(1)->update(['license_status'=>0]);
            return Redirect::back()->with('error_msg', $result['message']);
        }

    }


}
