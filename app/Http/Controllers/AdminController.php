<?php

namespace App\Http\Controllers;

use App\Category;
use App\Currency;
use App\Location;
use App\Order;
use Config;
use App\Setting;
use App\Admin;
use App\Shop;
use Illuminate\Support\Facades\Hash;
use App\User;
use Artisan;
use Auth;
use Illuminate\Http\Request;
use Redirect;



class AdminController extends Controller
{
    public function owner_login(Request $request)
    {

        $request->validate([
            'email' => 'bail|required|email',
            'password' => 'bail|required',
        ]);

        $userdata = array(
            'email' => $request->email,
            'password' => $request->password,
            'role' => 1,
        );

        if (Auth::attempt($userdata)) {
            return redirect('owner/home');
        } else {
            return Redirect::back()->with('error_msg', 'Invalid Username or Password');
        }
    }

    public function admin_home()
    {
        $master = array();
        $master['sales'] = 0;
        $master['shops'] = Shop::get()->count();
        $master['users'] = User::where('role', 0)->get()->count();
        $master['delivery'] = User::where('role', 2)->get()->count();

        $currency_code = Setting::where('id', 1)->first()->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;

        $sales = Order::get();
        foreach ($sales as $value) {
            $master['sales'] = $master['sales'] + $value->payment;
        }
        $users = User::where('role', 0)->orderBy('id', 'DESC')->get();
        foreach ($users as $value) {
            $value->orders = Order::where('customer_id', $value->id)->get()->count();
        }
        $categories = Category::orderBy('id', 'DESC')->get();
        $owners = User::where('role', 1)->orderBy('id', 'DESC')->get();
        $location = Location::orderBy('id', 'DESC')->get();
        $shops = Shop::orderBy('id', 'DESC')->get();
        foreach ($shops as $value) {
            $earning = 0;
            $a = Order::where([['payment_status', 1], ['shop_id', $value->id]])->get();
            foreach ($a as $item) {
                $earning = $earning + $item->payment;
            }
            $value->earning = $earning;
        }
        return view('mainAdmin.home', ['master' => $master, 'users' => $users, 'category' => $categories, 'owners' => $owners, 'currency' => $currency, 'locations' => $location, 'shops' => $shops]);
    }

    public function saveEnvData(Request $request){
        $request->validate([
            'email' => 'bail|required',
            'password' => 'bail|required|min:6',
        ]);

        $data['DB_HOST']=$request->db_host;
        $data['DB_DATABASE']=$request->db_name;
        $data['DB_USERNAME']=$request->db_user;
        $data['DB_PASSWORD']=$request->db_pass;

        $envFile = app()->environmentFilePath();

        if($envFile){
            $str = file_get_contents($envFile);
            if (count($data) > 0) {
                foreach ($data as $envKey => $envValue) {
                    $str .= "\n"; // In case the searched variable is in the last line without \n
                    $keyPosition = strpos($str, "{$envKey}=");
                    $endOfLinePosition = strpos($str, "\n", $keyPosition);
                    $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                    // If key does not exist, add it
                    if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                        $str .= "{$envKey}={$envValue}\n";
                    } else {
                        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                    }
            }
            }
            $str = substr($str, 0, -1);
            if (!file_put_contents($envFile, $str)){
                return response()->json(['data' => null,'success'=>false], 200);
            }
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            return response()->json([ 'data' => null,'success'=>true], 200);
        }
    }

    public function saveAdminData(Request $request){
        $request->validate([
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6',
        ]);
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Admin::find(1)->update(['email'=>$request->email,'password'=>Hash::make($request->password)]);
        Setting::find(1)->update(['license_status'=>1,'license_key'=>$request->license_code,'license_name'=>$request->client_name]);
        return response()->json([ 'data' => url('mainAdmin/login'),'success'=>true], 200);
    }
}
