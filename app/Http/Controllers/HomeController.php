<?php

namespace App\Http\Controllers;

use Auth;
use App\Shop;
use App\Order;
use App\Setting;
use App\Currency;
use App\GroceryOrder;
use Carbon\Carbon;
use App\Item;
use App\User;
// use Paytabs;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // return view('home');

        $shop_id = array();
        $master = array();
        $master['sales']= 0;
        $master['shops'] = Shop::where('user_id',Auth::user()->id)->get()->count();
        $master['users'] = User::where('role',0)->get()->count();
        $master['delivery'] = User::where('role',2)->get()->count();
        $sales = Order::where('owner_id',Auth::user()->id)->get();

        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;

        foreach ($sales as $value) {
            $master['sales'] = $master['sales'] + $value->payment;
        }
        $users = User::where('role',0)->orderBy('id', 'DESC')->get();
        foreach ($users as $value) {
            $value->orders = Order::where([['customer_id',$value->id],['owner_id',Auth::user()->id]])->get()->count();
        }
        $shops = Shop::where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        foreach (Auth::user()->shops as $value) {
            array_push($shop_id,$value->id);
        }
        $date = Carbon::now();

        $items = Item::whereIn('shop_id',$shop_id)->orderBy('id', 'DESC')->get();
        // $orders = Order::with(['location','shop','customer','deliveryGuy','orderItem'])
        // ->whereBetween('created_at', [ $date->format('Y-m-d'). " 00:00:00",  $date->format('Y-m-d') . " 23:59:59"])
        // ->orderBy('id', 'DESC')
        // ->get();
        $orders = Order::where([['owner_id',Auth::user()->id],['order_status','Pending']])
        ->with(['location','shop','customer','deliveryGuy','orderItem'])
        ->orderBy('id', 'DESC')
        ->get();

        $groceryOrders =  GroceryOrder::with(['shop','customer','deliveryGuy'])->where([['owner_id',Auth::user()->id],['order_status','Pending']])->orderBy('id', 'DESC')->paginate(10);
        return view('admin.dashboard',['master'=>$master,'groceryOrders'=>$groceryOrders,'users'=>$users,'shops'=>$shops,'items'=>$items,'currency'=>$currency,'orders'=>$orders]);
    }

    public function paytabsPayment()
    {
        // $pt = Paytabs::getInstance("bansi.thirstydevs@gmail.com", "fUCC5bEtajZdPSm5s4AumaMU5QOyH9SW4vy9GdDRwD4lwqrYv2sRdeLi5AQMKJJOeiYVvCM2fQoMhPKNScE5rqox0ekLY3zcmy8S");
        // // dd($pt);
        // $result = $pt->create_pay_page(array(
        //     "merchant_email" => "bansi.thirstydevs@gmail.com",
        //     'secret_key' => "fUCC5bEtajZdPSm5s4AumaMU5QOyH9SW4vy9GdDRwD4lwqrYv2sRdeLi5AQMKJJOeiYVvCM2fQoMhPKNScE5rqox0ekLY3zcmy8S",
        //     'title' => "John Doe",
        //     'cc_first_name' => "John",
        //     'cc_last_name' => "Doe",
        //     'email' => "customer@email.com",
        //     'cc_phone_number' => "973",
        //     'phone_number' => "33333333",
        //     'billing_address' => "Juffair, Manama, Bahrain",
        //     'city' => "Manama",
        //     'state' => "Capital",
        //     'postal_code' => "97300",
        //     'country' => "BHR",
        //     'address_shipping' => "Juffair, Manama, Bahrain",
        //     'city_shipping' => "Manama",
        //     'state_shipping' => "Capital",
        //     'postal_code_shipping' => "97300",
        //     'country_shipping' => "BHR",
        //     "products_per_title"=> "Mobile Phone",
        //     'currency' => "BHD",
        //     "unit_price"=> "10",
        //     'quantity' => "1",
        //     'other_charges' => "0",
        //     'amount' => "10.00",
        //     'discount'=>"0",
        //     "msg_lang" => "english",
        //     "reference_no" => "1231231",
        //     "site_url" => "www.test.com",
        //     'return_url' =>url('/')."/check_payment",
        //     "cms_with_version" => "API USING PHP"
        // ));

        // if($result->response_code == 4012){
        //     return redirect($result->payment_url);
        // }else{
        //     return $result->result;
        // }
    }

    public function check_payment(Request $request)
    {
        // $pt = Paytabs::getInstance("bansi.thirstydevs@gmail.com", "fUCC5bEtajZdPSm5s4AumaMU5QOyH9SW4vy9GdDRwD4lwqrYv2sRdeLi5AQMKJJOeiYVvCM2fQoMhPKNScE5rqox0ekLY3zcmy8S");
        // $result = $pt->verify_payment($request->payment_reference);
        // dd($result);
        // if($result->response_code == 100){
        //     dd('payment is completed successfully');
        // }
    }
}
