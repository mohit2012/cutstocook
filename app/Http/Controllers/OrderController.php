<?php

namespace App\Http\Controllers;

use App\Order;
use App\Setting;
use App\Currency;
use App\User;
use App\Shop;
use App\OrderChild;
use App\Coupon;
use App\OwnerSetting;
use App\UserPoint;
use OneSignal;
use App\GroceryOrder;
use App\UserAddress;
use Twilio\Rest\Client;
use App\GroceryShop;
use App\CompanySetting;
use DB;
use Config;
use App\AdminNotification;
use App\Review;
use App\PointLog;
use Carbon\Carbon;
use App\Notification;
use App\NotificationTemplate;
use App\Mail\OrderStatus;
use App\Mail\OrderCreate;
use Illuminate\Support\Facades\Mail;
use Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = Order::with(['location','shop','customer','deliveryGuy','orderItem'])->orderBy('id', 'DESC')->paginate(10);
        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
        return view('mainAdmin.order.orders',['orders'=>$data,'currency'=>$currency]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

        $data = Order::with(['location','shop','customer','deliveryGuy','orderItem'])->findOrFail($id);

        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;

        return view('mainAdmin.order.singleOrder',['data'=>$data,'currency'=>$currency]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {
            $Notification = Notification::where('order_id',$id)->get();
            if(count($Notification)>0){
                foreach ($Notification as $n) {
                    $n->delete();
                }
            }
            $Review = Review::where('order_id',$id)->get();
            if(count($Review)>0){
                foreach ($Review as $r) {
                    $r->delete();
                }
            }
            $OrderChild = OrderChild::where('order_id',$id)->get();
            if(count($OrderChild)>0){
                foreach ($OrderChild as $oc) {
                    $oc->delete();
                }
            }
            $AdminNotification = AdminNotification::where('order_id',$id)->get();
            if(count($AdminNotification)>0){
                foreach ($AdminNotification as $an) {
                    $an->delete();
                }
            }


            $delete = Order::findOrFail($id);
            $delete->delete();
            return 'true';
        } catch (\Exception $e) {
            return response('Data is Connected with other Data', 400);
        }

        // $delete = Order::findOrFail($id);
        // $delete->delete();
        // return 'true';
    }

    public function viewOrder()
    {
        $totalOrder = Order::where('owner_id',Auth::user()->id)->get()->count();
        $data = Order::where('owner_id',Auth::user()->id)->with(['location','shop','customer','deliveryGuy','orderItem'])->orderBy('id', 'DESC')->paginate(15);
        $pendingOrder = Order::where([['owner_id',Auth::user()->id],['order_status','Pending']])->with(['location','shop','customer','deliveryGuy','orderItem'])->orderBy('id', 'DESC')->get();
        $approveOrder = Order::where([['owner_id',Auth::user()->id],['order_status','Approved']])
        ->orWhere([['owner_id',Auth::user()->id],['order_status','DriverApproved']])
        ->with(['location','shop','customer','deliveryGuy','orderItem'])
        ->orderBy('id', 'DESC')->get();
        $onWayOrder = Order::where([['owner_id',Auth::user()->id],['order_status','Prepare']])
        ->orWhere([['owner_id',Auth::user()->id],['order_status','DriverAtShop']])
        ->orWhere([['owner_id',Auth::user()->id],['order_status','PickUpFood']])
        ->orWhere([['owner_id',Auth::user()->id],['order_status','OnTheWay']])
        ->orWhere([['owner_id',Auth::user()->id],['order_status','DriverReach']])
        ->with(['location','shop','customer','deliveryGuy','orderItem'])
        ->orderBy('id', 'DESC')->get();
        $completeOrder = Order::where([['owner_id',Auth::user()->id],['order_status','Delivered']])->with(['location','shop','customer','deliveryGuy','orderItem'])->orderBy('id', 'DESC')->get();
        $cancelOrder = Order::where([['owner_id',Auth::user()->id],['order_status','Cancel']])->with(['location','shop','customer','deliveryGuy','orderItem'])->orderBy('id', 'DESC')->get();

        $deliveryBoy = User::where('role',2)->get();
        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;

       return view('admin.order.orders',['orders'=>$data,'totalOrder'=>$totalOrder,'deliveryBoy'=>$deliveryBoy,'currency'=>$currency,'pendingOrder'=>$pendingOrder,'approveOrder'=>$approveOrder,'onWayOrder'=>$onWayOrder,'completeOrder'=>$completeOrder,'cancelOrder'=>$cancelOrder]);
    }

    public function changeOrderStatus(Request $request){

        $order = Order::findOrFail($request->id);
        $status = $request->status;
        if($order->payment_status==0 && $status=="Delivered"){
            return response()->json(['data' =>$order ,'success'=>false  ], 200);
        }
        else{
            Order::findOrFail($request->id)->update(['order_status'=>$request->status]);
            $order = Order::findOrFail($request->id);
            $user = User::findOrFail($order->customer_id);
            if($status=='Cancel' || $status=='Approved' || $status=='Delivered' || $status=='Prepare' || $status =="OnTheWay")
            {
                $notification = Setting::findOrFail(1);
                $shop_name = CompanySetting::where('id',1)->first()->name;
                $content = NotificationTemplate::where('title','Order Status')->first()->mail_content;
                $message = NotificationTemplate::where('title','Order Status')->first()->message_content;
                $detail['name'] = $user->name;
                $detail['order_no'] = $order->order_no;
                $detail['shop'] =Shop::findOrFail($order->shop_id)->name;
                $detail['status'] =$status;
                $detail['shop_name'] = $shop_name;


                if($notification->mail_notification==1){

                    try {
                        // Mail::to($user)->send(new OrderStatus($content,$detail));
                    } catch (\Throwable $th) {
                        //throw $th;
                    }

                }
                if($notification->sms_twilio ==1){
                    $sid = $notification->twilio_account_id;
                    $token = $notification->twilio_auth_token;
                    // $client = new Client($sid, $token);
                    // $data = ["{{name}}", "{{order_no}}", "{{shop}}","{{status}}", "{{shop_name}}"];
                    // $message1 = str_replace($data, $detail, $message);

                    // $client->messages->create(
                    // '+918758164348',
                    //     array(
                    //         'from' => $notification->twilio_phone_number,
                    //         'body' =>  $message1
                    //     )
                    // );
                }
                if($notification->push_notification ==1){
                    if($user->device_token!=null){
                        Config::set('onesignal.app_id', env('APP_ID'));
                        Config::set('onesignal.rest_api_key', env('REST_API_KEY'));
                        Config::set('onesignal.user_auth_key', env('USER_AUTH_KEY'));

                        $data = ["{{name}}", "{{order_no}}", "{{shop}}","{{status}}", "{{shop_name}}"];
                        $message1 = str_replace($data, $detail, $message);
                        $userId=$user->device_token;
                        try{
                        OneSignal::sendNotificationToUser(
                            $message1,
                            $userId,
                            $url = null,
                            $data = null,
                            $buttons = null,
                            $schedule = null
                        );
                    }catch (\Exception $e) {

                    }
                    }
                }
                if($order->deliveryBoy_id !=null){
                    $driver = User::findOrFail($order->deliveryBoy_id);
                    $driverMessage = NotificationTemplate::where('title','Order Status')->first()->message_content;
                    $driverDetail['name'] = $driver->name;
                    $driverDetail['order_no'] = $order->order_no;
                    $driverDetail['shop'] =Shop::findOrFail($order->shop_id)->name;
                    $driverDetail['status'] =$status;
                    $driverDetail['shop_name'] = $shop_name;
                    if($notification->push_notification ==1){
                        if($driver->device_token!=null){
                            Config::set('onesignal.app_id', env('APP_ID'));
                            Config::set('onesignal.rest_api_key', env('REST_API_KEY'));
                            Config::set('onesignal.user_auth_key', env('USER_AUTH_KEY'));

                            $driverData = ["{{name}}", "{{order_no}}", "{{shop}}","{{status}}", "{{shop_name}}"];
                            $driverMessage1 = str_replace($driverData, $driverDetail, $driverMessage);
                            $device_token=$driver->device_token;
                            try{
                            OneSignal::sendNotificationToUser(
                                $driverMessage1,
                                $device_token,
                                $url = null,
                                $data = null,
                                $buttons = null,
                                $schedule = null
                            );
                        }catch (\Exception $e) {

                        }
                        }
                    }

                    $driverData = ["{{name}}", "{{order_no}}", "{{shop}}","{{status}}", "{{shop_name}}"];
                    $driverMessage1 = str_replace($driverData, $driverDetail, $driverMessage);
                    $image = NotificationTemplate::where('title','Order Status')->first()->image;

                    $driverData1 = array();
                    $driverData1['driver_id']= $order->deliveryBoy_id;
                    $driverData1['order_id']= $order->id;
                    $driverData1['title']= 'Order '.$status;
                    $driverData1['message']= $driverMessage1;
                    $driverData1['image'] = $image;
                    $driverData1['notification_type'] = "Food";
                    Notification::create($driverData1);
                }
                $data = ["{{name}}", "{{order_no}}", "{{shop}}","{{status}}", "{{shop_name}}"];
                $message1 = str_replace($data, $detail, $message);
                $image = NotificationTemplate::where('title','Order Status')->first()->image;

                $data1 = array();
                $data1['user_id']= $order->customer_id;
                $data1['order_id']= $order->id;
                $data1['title']= 'Order '.$status;
                $data1['message']= $message1;
                $data1['image'] = $image;
                $data1['notification_type'] = "Food";
                Notification::create($data1);
            }

            return response()->json(['data' =>$order ,'success'=>true], 200);
        }
    }

    public function changePaymentStatus(Request $request){

        $order = Order::findOrFail($request->id);
        if($request->status==1){
            Order::findOrFail($request->id)->update(['payment_status'=>$request->status]);
        }
        else if($request->status==2){
            Order::findOrFail($request->id)->update(['payment_status'=>$request->status]);
        }
        return response()->json(['data' =>$order ,'success'=>true], 200);
    }

    public function viewsingleOrder($id){
        $data = Order::with(['location','shop','customer','deliveryGuy','orderItem'])->findOrFail($id);

        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;

        return view('admin.order.singleOrder',['data'=>$data,'currency'=>$currency]);
    }

    public function ordersInvoice($id){
        $data = Order::with(['location','shop','customer','deliveryGuy','orderItem'])->where('id',$id)->first();
        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
        return view('admin.order.invoice',['data'=>$data,'currency'=>$currency]);
    }

    public function printInvoice($id){
        $data = Order::with(['location','shop','customer','deliveryGuy','orderItem'])->where('id',$id)->first();
        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
        return view('admin.order.invoicePrint',['data'=>$data,'currency'=>$currency]);
    }

    public function viewUserOrder(){
        $master = array();
        $master['past_order'] = Order::where([['customer_id',Auth::user()->id],['order_status','Delivered']])
        ->orWhere([['customer_id',Auth::user()->id],['order_status','Cancel']])
        ->with(['shop','orderItem'])
        ->orderBy('id', 'DESC')->get();

        $master['current_order'] = Order::where([['customer_id',Auth::user()->id],['order_status','Pending']])
        ->orWhere([['customer_id',Auth::user()->id],['order_status','Approved']])
        ->orWhere([['customer_id',Auth::user()->id],['order_status','DriverApproved']])
        ->orWhere([['customer_id',Auth::user()->id],['order_status','Prepare']])
        ->orWhere([['customer_id',Auth::user()->id],['order_status','DriverAtShop']])
        ->orWhere([['customer_id',Auth::user()->id],['order_status','PickUpFood']])
        ->orWhere([['customer_id',Auth::user()->id],['order_status','OnTheWay']])
        ->orWhere([['customer_id',Auth::user()->id],['order_status','DriverReach']])
        ->with(['shop','orderItem'])
        ->orderBy('id', 'DESC')->get();

        return response()->json(['success'=>true,'msg'=>null ,'data' =>$master ], 200);
    }

    public function createOrder(Request $request){
        $request->validate([
            'shop_id' => 'bail|required',
            'payment' => 'bail|required',
            'shop_charge' => 'bail|required',
            'delivery_charge' => 'bail|required',
            'itemData' => 'bail|required',
            'payment_status' => 'bail|required',
            'payment_type' => 'bail|required',
            'payment_token' => 'required_if:payment_type,STRIPE,PAYPAL,RAZOR',
        ]);
        $data = $request->all();
        if(isset($request->coupon_id))
        {
            $count = Coupon::findOrFail($request->coupon_id)->use_count;
            $count = $count +1;
            Coupon::findOrFail($request->coupon_id)->update(['use_count'=>$count]);
        }
        $data['customer_id'] = Auth::user()->id;
        $data['owner_id'] = Shop::findOrFail($request->shop_id)->user_id;
        $data['order_status'] = OwnerSetting::where('user_id',$data['owner_id'])->first()->default_food_order_status;
        $data['order_no'] = '#' . rand(100000, 999999);
        $data['delivery_type'] = 'Deliver';
        $data['address_id'] = User::find(Auth::user()->id)->address_id;
        $data['time'] = Carbon::now('Asia/Kolkata')->format('h:i A');
        $data['date'] = Carbon::now()->format('Y-m-d');
        $data['driver_otp']= rand(100000, 999999);
        $order = Order::create($data);
        foreach (json_decode($data['itemData']) as $value)
        {
            $child['order_id'] = $order->id;
            $child['item'] = $value->item;
            if(isset($value->package_id))
            {
                $child['package_id'] = $value->package_id;
            }
            $child['price'] = $value->price;
            $child['quantity'] = $value->quantity;
            OrderChild::create($child);
        }

        // user notification
        $user = User::findOrFail($order->customer_id);
        $notification = Setting::findOrFail(1);
        $shop_name = CompanySetting::where('id',1)->first()->name;
        $content = NotificationTemplate::where('title','Create Order')->first()->mail_content;
        $message = NotificationTemplate::where('title','Create Order')->first()->message_content;
        $detail['name'] = $user->name;
        $detail['shop'] =Shop::findOrFail($order->shop_id)->name;
        $detail['shop_name'] = $shop_name;

        if($notification->mail_notification==1){
            try {
                Mail::to($user)->send(new OrderCreate($content,$detail));
            } catch (\Throwable $th) {
                //throw $th;
            }

        }
        if($notification->sms_twilio ==1){
            // $sid = $notification->twilio_account_id;
            // $token = $notification->twilio_auth_token;
            // $client = new Client($sid, $token);
            // $data = ["{{name}}", "{{shop}}","{{shop_name}}"];
            // $message1 = str_replace($data, $detail, $message);
            // $client->messages->create(
            // '+918758164348',
            //     array(
            //         'from' => $notification->twilio_phone_number,
            //         'body' =>  $message1
            //     )
            // );
        }
        if($notification->push_notification ==1){
            if($user->device_token!=null){
                Config::set('onesignal.app_id', env('APP_ID'));
                Config::set('onesignal.rest_api_key', env('REST_API_KEY'));
                Config::set('onesignal.user_auth_key', env('USER_AUTH_KEY'));

                $data = ["{{name}}", "{{shop}}","{{shop_name}}"];
                $message1 = str_replace($data, $detail, $message);
                $userId=$user->device_token;
                try{
                OneSignal::sendNotificationToUser(
                    $message1,
                    $userId,
                    $url = null,
                    $data = null,
                    $buttons = null,
                    $schedule = null
                );
                }catch (\Exception $e) {

                }
            }
        }
        $data = ["{{name}}", "{{shop}}","{{shop_name}}"];
        $message1 = str_replace($data, $detail, $message);
        $image = NotificationTemplate::where('title','Create Order')->first()->image;
        $data1 = array();
        $data1['user_id']= $order->customer_id;
        $data1['order_id']= $order->id;
        $data1['title']= 'Order Created';
        $data1['message']= $message1;
        $data1['image'] = $image;
        $data1['notification_type'] = "Food";
        Notification::create($data1);

        // owner notification
        $owner_id = Shop::where('id',$order->shop_id)->first()->user_id;
        $web_notification = OwnerSetting::where('user_id',$owner_id)->first()->web_notification;
        $message_noti = NotificationTemplate::where('title','Order Arrive')->first()->message_content;
        $shop = Shop::findOrFail($order->shop_id)->name;
        $shop_name = CompanySetting::findOrFail(1)->name;
        $detail1['name'] = User::findOrFail($owner_id)->name;
        $detail1['order_no'] = $order->order_no;
        $detail1['shop'] =$shop;
        $detail1['customer_name'] = $user->name;
        $detail1['shop_name'] = $shop_name;
        $data1 = ["{{name}}", "{{order_no}}", "{{shop}}", "{{customer_name}}", "{{shop_name}}"];
        $message1 = str_replace($data1, $detail1, $message_noti);
        $admin_web_noti = Setting::find(1)->web_notification;
        // if($admin_web_noti == 1){
        // if($web_notification ==1){
            $userId = User::find($owner_id)->device_token;
            if($userId!=null){

            // Config::set('onesignal.app_id', env('APP_ID_WEB'));
            // Config::set('onesignal.rest_api_key', env('REST_API_KEY_WEB'));
            // Config::set('onesignal.user_auth_key', env('USER_AUTH_KEY_WEB'));
            Config::set('onesignal.app_id', env('APP_ID'));
            Config::set('onesignal.rest_api_key', env('REST_API_KEY'));
            Config::set('onesignal.user_auth_key', env('USER_AUTH_KEY'));
            // $url = url('owner/viewOrder/'.$order->id.$order->order_no);
            try{
            OneSignal::sendNotificationToUser(
                $message1,
                $userId,
                $url = null,
                $data = null,
                $buttons = null,
                // $wp_wns_sound = 'http://192.168.0.107:10/images/salamisound-2555311-ding-dong-bell-doorbell.wav',
                $schedule = null
            );
            }catch (\Exception $e) {

            }
            }
        // }
        // }
        $data_noti['owner_id'] =  User::findOrFail($owner_id)->id;
        $data_noti['user_id'] =  $order->customer_id;
        $data_noti['order_id'] =  $order->id;
        $data_noti['message'] =   $message1;
        AdminNotification::create($data_noti);

        // driver notification
        $message_noti = NotificationTemplate::where('title','Order Request')->first()->message_content;
        $drivers = User::where([['role',2],['driver_available',1]])->get();
        foreach ($drivers as $driver){
            $lat= $driver->lat;
            $lng= $driver->lang;
            $radius = $driver->driver_radius;
            $shopResult = DB::select(DB::raw('SELECT id,radius, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians(latitude) ) ) ) AS distance FROM shop  WHERE id = '.$order->shop_id.'   HAVING distance < '.$radius.'  ORDER BY distance'));
            $userResult = DB::select(DB::raw('SELECT id, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lang ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians(lat) ) ) ) AS distance FROM users WHERE id = '.$order->customer_id.'  HAVING distance < '.$radius.'  ORDER BY distance'));

            if(count($shopResult)>0 && count($userResult)>0 ){
                $customer = User::findOrFail($order->customer_id);
                $address = UserAddress::findOrFail($customer->address_id);
                $driverDetail['name'] = $driver->name;
                $driverDetail['order_no'] = $order->order_no;
                $driverDetail['user_address'] =$address->soc_name.' ,'.$address->street.' ,'.$address->city;
                $driverDetail['shop'] =Shop::findOrFail($order->shop_id)->name;
                $driverDetail['shop_name'] = CompanySetting::findOrFail(1)->name;
                $driverData = ["{{name}}", "{{order_no}}", "{{user_address}}", "{{shop}}", "{{shop_name}}"];
                $driverMessage = str_replace($driverData, $driverDetail, $message_noti);
                $userId=$driver->device_token;
                if($userId!=null){
                    Config::set('onesignal.app_id', env('APP_ID'));
                    Config::set('onesignal.rest_api_key', env('REST_API_KEY'));
                    Config::set('onesignal.user_auth_key', env('USER_AUTH_KEY'));
                    try{
                    OneSignal::sendNotificationToUser(
                        $driverMessage,
                        $userId,
                        $url = null,
                        $data = null,
                        $buttons = null,
                        $schedule = null
                    );
                }catch (\Exception $e) {

                }
                }
                $image = NotificationTemplate::where('title','Order Request')->first()->image;
                $driverData1 = array();
                $driverData1['driver_id']= $driver->id;
                $driverData1['order_id']= $order->id;
                $driverData1['title']= 'Order Request';
                $driverData1['message']= $driverMessage;
                $driverData1['image'] = $image;
                $driverData1['notification_type'] = "Food";
                Notification::create($driverData1);

            }

        }
        return response()->json(['msg' => null, 'data' => $order,'success'=>true], 200);
    }

    public function orderCreateNoti(){
        // $userId= '4712d4f0-7ebf-4d00-9b4b-056dc27678ac';

        $userId = Auth::user()->device_token;
        $message = NotificationTemplate::where('title','Order Arrive')->first()->message_content;
        $shop_name = CompanySetting::findOrFail(1)->name;

        $detail['name'] = Auth::user()->name;
        $detail['order_no'] = '#1233444';
        $detail['shop'] ='24 Pizza town';
        $detail['customer_name'] = 'Pinky';
        $detail['shop_name'] = $shop_name;
        $data = ["{{name}}", "{{order_no}}", "{{shop}}", "{{customer_name}}", "{{shop_name}}"];

        $message1 = str_replace($data, $detail, $message);
        $url = 'http://192.168.0.109:10/viewOrder/1#564344';

        Config::set('onesignal.app_id', env('APP_ID_WEB'));
        Config::set('onesignal.rest_api_key', env('REST_API_KEY_WEB'));
        Config::set('onesignal.user_auth_key', env('USER_AUTH_KEY_WEB'));
        try{
        OneSignal::sendNotificationToUser(
            $message1,
            $userId,
            $url = $url,
            $data = null,
            $buttons = null,
            // $wp_wns_sound = 'http://192.168.0.109:10/images/salamisound-2555311-ding-dong-bell-doorbell.wav',
            $schedule = null
        );
    }catch (\Exception $e) {

    }


        return redirect()->back();
    }

    public function assignOrder(Request $request){
       $data = $request->all();
        if($request->deliveryBoy!= null){
            $data = Order::findOrFail($request->id)->update(['deliveryBoy_id'=>$request->deliveryBoy]);
        }
        return response()->json(['msg' => null, 'data' => $data,'success'=>true], 200);
    }

    public function singleOrder($id){

        $data = Order::where('id',$id)->with(['shop','orderItem'])->first();
        $data['shop_review'] = Review::where([['order_id',$data->id],['shop_id',$data->shop_id]])->first();
        $data['driver_review'] = Review::where([['order_id',$data->id],['deliveryBoy_id',$data->deliveryBoy_id]])->first();
        $item = array();
        $package_review = array();
        $item =  Review::where('order_id',$data->id)->whereIn('item_id',explode(",",$data->items))->orderBy('id', 'DESC')->get()->toArray();
        $package_review = Review::where('order_id',$data->id)->whereIn('package_id',explode(",",$data->package_id))->orderBy('id', 'DESC')->get()->toArray();
        $data['item_review']=array_merge($item,$package_review);
        if($data->deliveryBoy_id!=null){
          $data['driver'] = User::findOrFail($data->deliveryBoy_id);
        }
        return response()->json(['success'=>true,'msg'=>null ,'data' =>$data ], 200);
    }

    public function revenueReport(){
        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
        $data = Order::with(['shop','customer'])->where('payment_status',1)->orderBy('id', 'DESC')->get();
        $shops = Shop::orderBy('id', 'DESC')->get();
        foreach ($data as  $value)
        {
            $value->shop_name = Shop::where('id',$value->shop_id)->first()->name;
        }
        return view('mainAdmin.order.revenueReport',['data'=>$data,'currency'=>$currency,'shops'=>$shops]);
    }

    public function rejectDriverOrder($id)
    {
        $order = Order::find($id);
        $likes=array_filter(explode(',',$order->reject_by));
        if(count(array_keys($likes,Auth::user()->id))>0){

        }
        else{
            array_push($likes,Auth::user()->id);
        }
        $driver =implode(',',$likes);
        $order = Order::find($id);
        $order->reject_by =$driver;
        $order->update();
        $a= Order::find($id);
        return response()->json(['msg' => null ,'data' =>$a, 'success'=>true], 200);
    }

    public function driverRequest()
    {
        $auth = Auth::user();
        $master = array();
        $radiusUser = array();
        $shop = array();
        $date = Carbon::now()->format('Y-m-d');
        $lat= $auth->lat;
        $lng= $auth->lang;
        $radius = $auth->driver_radius;
        $results = DB::select(DB::raw('SELECT id,radius, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians(latitude) ) ) ) AS distance FROM shop HAVING distance < '.$radius.'  ORDER BY distance'));
        if(count($results)>0){
            foreach ($results as $q) {
                array_push($shop, $q->id);
            }
        }
        $userResult = DB::select(DB::raw('SELECT id, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lang ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians(lat) ) ) ) AS distance FROM users WHERE role = 0 HAVING distance < '.$radius.'  ORDER BY distance'));
        if(count($userResult)>0){
            foreach ($userResult as $value) {
                array_push($radiusUser, $value->id);
            }
        }
        $pending_order = array();
        $approve_order = array();
        $pending = Order::with(['shop'])->orWhereIn('customer_id',$radiusUser)->whereIn('shop_id',$shop)->where('order_status','Pending')
        ->whereDate('date',$date)->orderBy('id', 'DESC')->get();
        foreach ($pending as $value) {
            if(in_array($auth->id, explode(',',$value->reject_by))==false){
                array_push($pending_order,$value->id);
            }
        }
        $pending = Order::with(['shop'])->whereIn('id',$pending_order)->orderBy('id', 'DESC')->get();
        foreach ($pending as $value) {
            $user= User::findOrFail($value->customer_id);
            $value->delivery_address =  UserAddress::find($value->address_id);
        }

        $approved = Order::with(['shop'])->orWhereIn('customer_id',$radiusUser)->whereIn('shop_id',$shop)->where('order_status','Approved')
        ->whereDate('date',$date)->orderBy('id', 'DESC')->get();
        foreach ($approved as $value) {
            if(in_array($auth->id, explode(',',$value->reject_by))==false){
                array_push($approve_order,$value->id);
            }
        }
        $approved = Order::with(['shop'])->whereIn('id',$approve_order)->orderBy('id', 'DESC')->get();
        foreach ($approved as $value) {
            $user= User::findOrFail($value->customer_id);
            $value->delivery_address = UserAddress::find($value->address_id);
        }
        $master['requests'] = array_merge($pending->toArray(), $approved->toArray());
        $master['accepted'] = Order::with(['shop'])->where([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','DriverApproved']])
        ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','Prepare']])
        ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','DriverAtShop']])
        ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','PickUpFood']])
        ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','OnTheWay']])
        ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','DriverReach']])
        ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','Delivered'],['payment_status',0]])
        ->orderBy('id', 'DESC')->get();

        foreach ($master['accepted'] as $value) {
            $user= User::findOrFail($value->customer_id);
            $value->delivery_address =  UserAddress::find($value->address_id);
        }

        if(Auth::user()->driver_radius==null){
            return response()->json(['success'=>true,'msg'=>null ,'data' =>[] ], 200);
        }
        else
        {
            if(Auth::user()->driver_available==1)
            {
                return response()->json(['success'=>true,'msg'=>null ,'data' =>$master ], 200);
            }
            else{
                return response()->json(['success'=>true,'msg'=>null ,'data' =>[] ], 200);
            }
        }
    }

    public function acceptRequest(Request $request)
    {
        $request->validate([
            'order_id' => 'bail|required',
        ]);
        $driver_id = Auth::user()->id;
        $order = Order::find($request->order_id);
        if($order)
        {
            if($order->order_status=="Approved"){
                $date = Carbon::now()->format('Y-m-d');
                $accepted = Order::where([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','DriverApproved']])
                ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','Prepare']])
                ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','DriverAtShop']])
                ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','PickUpFood']])
                ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','OnTheWay']])
                ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','DriverReach']])
                ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','Delivered'],['payment_status',0]])
                ->get();

                if(count($accepted)==0){
                    Order::findOrFail($order->id)->update(['deliveryBoy_id'=>$driver_id,'order_status'=>'DriverApproved']);
                    $data = Order::findOrFail($order->id);
                    return response()->json(['success'=>true,'msg'=>'Order successfully assign to you!' ,'data' =>$data ], 200);
                }
                else{
                    return response()->json(['success'=>false,'msg'=>"You have already one incomplete order, You can't accept this order.",'data' =>null ], 200);
                }
            }
            else if($order->order_status=="Pending"){
                return response()->json(['success'=>false,'msg'=>'Order is not approved by shop' ,'data' =>null ], 200);
            }
            else{
                return response()->json(['success'=>false,'msg'=>'Already Assign to driver.' ,'data' =>null ], 200);
            }
        }
        else
        {
            return response(['success' => false , 'data' => 'No record found this order']);
        }
    }

    public function driverTrip(Request $request)
    {
        $date = Carbon::now()->format('Y-m-d');
        $master= array();
        $master['collect'] =0;
        $master['order']= Order::with(['shop'])->where([['date',$date],['order_status','Pending']])
        ->orWhere([['date',$date],['order_status','Approved']])
        ->orderBy('id', 'DESC')->get();
        foreach ($master['order'] as $value) {
            $user= User::findOrFail($value->customer_id);
            $value->delivery_address =  UserAddress::find($value->address_id);
        }
        $master['grocery_order']= GroceryOrder::with(['shop'])->where([['date',$date],['order_status','Pending']])
        ->orWhere([['date',$date],['order_status','Approved']])
        ->orderBy('id', 'DESC')->get();
        foreach ($master['order'] as $value) {
            $user= User::findOrFail($value->customer_id);
            $value->delivery_address = UserAddress::find($value->address_id);
        }
        $foodtrip = Order::where([['date',$date],['deliveryBoy_id',Auth::user()->id]])->get()->count();
        $grocerytrip = GroceryOrder::where([['date',$date],['deliveryBoy_id',Auth::user()->id]])->get()->count();
        $master['trip']= $foodtrip + $grocerytrip;
        $master['cancled']= Order::where([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','Cancel']])->get()->count();
        $orders= Order::where([['date',$date],['deliveryBoy_id',Auth::user()->id]])->get();
        foreach ($orders as $value) {
            $master['collect'] = $master['collect'] + $value->payment;
        }
        $groceryOrders= GroceryOrder::where([['date',$date],['deliveryBoy_id',Auth::user()->id]])->get();
        foreach ($groceryOrders as $value) {
            $master['collect'] = $master['collect'] + $value->payment;
        }

        return response()->json(['success'=>true,'msg'=>null ,'data' =>$master ], 200);
    }

    public function driverStatus(Request $request){

        $request->validate( [
            'status' => 'bail|required',
            'order_id' => 'bail|required',
        ]);

        Order::findOrFail($request->order_id)->update(['order_status'=>$request->status]);

        $a = Order::findOrFail($request->order_id);
        $a->shop = Shop::findOrFail($a->shop_id);
        $a->customer = User::findOrFail($a->customer_id);

        return response()->json(['success'=>true,'msg'=>'status Updated' ,'data' =>$a ], 200);
    }

    public function collectPayment($id)
    {
        Order::findOrFail($id)->update(['payment_status'=>1]);
        $a = Order::findOrFail($id);
        $a->shop = Shop::findOrFail($a->shop_id);
        $a->customer = User::findOrFail($a->customer_id);
        return response()->json(['success'=>true,'msg'=>'Payment Collected successfully!' ,'data' =>$a ], 200);
    }

    public function revenueFilter(Request $request){

        $filterData = $request->all();

        if($request->shop_id==null && $request->reportPeriod==null && $request->period_day==null){
            $data = Order::with(['shop','customer'])
            ->where('payment_status',1)
            ->orderBy('id', 'DESC')
            ->get();
        }
        else if($request->shop_id==null && $request->reportPeriod==null && $request->period_day!=null){
            $data = Order::with(['shop','customer'])
            ->where([['date',$request->period_day],['payment_status',1]])
            ->orderBy('id', 'DESC')
            ->get();
        }
        else if($request->reportPeriod==null && $request->shop_id!=null){
            $data = Order::with(['shop','customer'])->where([['payment_status',1],['shop_id',$request->shop_id]])->orderBy('id', 'DESC')->get();
        }
        else if($request->shop_id!=null ){
            if($request->reportPeriod=="month"){
                $day = Carbon::parse(Carbon::now()->year.'-'.$request->period_month.'-01')->daysInMonth;
                $data = Order::with(['shop','customer'])
                    ->where([['shop_id',$request->shop_id],['payment_status',1]])
                    ->whereBetween('date', [ Carbon::now()->year."-".$request->period_month."-01",  Carbon::now()->year."-".$request->period_month."-".$day])
                    ->orderBy('id', 'DESC')
                    ->get();
            }
            elseif($request->reportPeriod=="year"){
                $data = Order::with(['shop','customer'])
                    ->where([['shop_id',$request->shop_id],['payment_status',1]])
                    ->whereBetween('date', [ $request->period_year."-01-01",  $request->period_year."-12-31"])
                    ->orderBy('id', 'DESC')
                    ->get();
            }
            elseif($request->reportPeriod=="week"){
                $date = Carbon::parse($request->period_week);
                $data = Order::with(['shop','customer'])
                    ->where([['shop_id',$request->shop_id],['payment_status',1]])
                    ->whereBetween('date', [ $date->startOfWeek()->format('Y-m-d'),  $date->endOfWeek()->format('Y-m-d')])
                    ->orderBy('id', 'DESC')
                    ->get();
            }
            elseif($request->reportPeriod=="day"){
                $data = Order::with(['shop','customer'])
                ->where([['shop_id',$request->shop_id],['date',$request->period_day],['payment_status',1]])
                ->orderBy('id', 'DESC')
                ->get();
            }
        }
        else if($request->shop_id==null){
            if($request->reportPeriod=="month"){

                $day = Carbon::parse(Carbon::now()->year.'-'.$request->period_month.'-01')->daysInMonth;
                $data = Order::with(['shop','customer'])
                    ->where('payment_status',1)
                    ->whereBetween('date', [ Carbon::now()->year."-".$request->period_month."-01",  Carbon::now()->year."-".$request->period_month."-".$day])
                    ->orderBy('id', 'DESC')
                    ->get();
            }
            elseif($request->reportPeriod=="year"){
                $data = Order::with(['shop','customer'])
                    ->where('payment_status',1)
                    ->whereBetween('date', [ $request->period_year."-01-01",  $request->period_year."-12-31"])
                    ->orderBy('id', 'DESC')
                    ->get();
            }
            elseif($request->reportPeriod=="week"){
                $date = Carbon::parse($request->period_week);
                $data = Order::with(['shop','customer'])
                    ->where('payment_status',1)
                    ->whereBetween('date', [ $date->startOfWeek()->format('Y-m-d'),$date->endOfWeek()->format('Y-m-d')])
                    ->orderBy('id', 'DESC')
                    ->get();
            }
            elseif($request->reportPeriod=="day"){

                $data = Order::with(['shop','customer'])
                ->where([['date',$request->period_day],['payment_status',1]])
                ->orderBy('id', 'DESC')
                ->get();
            }
        }

        foreach ($data as  $value) {
            $value->shop_name = Shop::where('id',$value->shop_id)->first()->name;
        }

        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
        $shops = Shop::orderBy('id', 'DESC')->get();
        return view('mainAdmin.order.revenueReport',['data'=>$data,'currency'=>$currency,'shops'=>$shops,'filterData'=>$filterData]);

    }

    public function cancelOrder($id){
        $order = Order::where('id',$id)->first();
        if($order){
            if($order->order_status=="Pending" || $order->order_status=="Approved" || $order->order_status=="DriverApproved" || $order->order_status=="Prepare" || $order->order_status=="DriverAtShop" ){
                if($order->payment_type = "LOCAL"){
                    Order::findOrFail($order->id)->update(['order_status'=>'Cancel']);
                    return response()->json(['success'=>true,'msg'=>"Your Order is canceled successfully." ,'data' =>null ], 200);
                }
                else if($order->payment_type = "STRIPE"){
                    Order::findOrFail($order->id)->update(['order_status'=>'Cancel',['payment_status'=>2]]);
                    return response()->json(['success'=>true,'msg'=>"Your Order is canceled successfully." ,'data' =>null ], 200);
                }
            }
            else
            {
                return response()->json(['success'=>false,'msg'=>"Sorry! we can't cancel this order." ,'data' =>null ], 200);
            }
        }
        else{
            return response()->json(['success'=>false,'msg'=>null ,'data' =>null ], 200);
        }
    }

    public function pickupFood(Request $request){
        $request->validate([
            'order_id' => 'bail|required',
            'otp' => 'bail|required',
        ]);
        $order = Order::where('id',$request->order_id)->first();
        if($order->driver_otp == $request->otp){
            Order::findOrFail($order->id)->update(['order_status'=>'PickUpFood']);
            $a = Order::findOrFail($order->id);
            return response()->json(['success'=>true,'msg'=>null ,'data' =>$a ], 200);
        }
        else{
            return response()->json(['success'=>false,'msg'=>'Incorrect OTP!' ,'data' =>null ], 200);
        }

    }

    public function getPendingOrder(){
        $orders = Order::where([['owner_id',Auth::user()->id],['order_status','Pending']])
        ->with(['location','shop','customer','deliveryGuy','orderItem'])
        ->orderBy('id', 'DESC')
        ->get();
        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
        return response()->json(['success'=>true,'msg'=>null ,'data' =>$orders,'currency'=>$currency ], 200);
    }

    public function viewOrderDetail($id){
        $a = Order::find($id);
        $a->shop = Shop::find($a->shop_id);
        $a->customer = User::find($a->customer_id)->makeHidden(['shops']);
        $a->driver = User::find($a->deliveryBoy_id,['lat','lang','role','id']);
        return response()->json(['success'=>true,'msg'=>null ,'data' =>$a ], 200);
    }

    public function earningHistroy(Request $request){
        if($request->start_date == "" && $request->end_date == "")
        {
            $food = Order::where([['deliveryBoy_id',Auth::user()->id],['order_status','Delivered'],['payment_status',1],['date',$request->date]])->orderBy('id', 'DESC')->get();
            $grocery = GroceryOrder::where([['deliveryBoy_id',Auth::user()->id],['order_status','Delivered'],['payment_status',1],['date',$request->date]])->orderBy('id', 'DESC')->get();
            $d_charge = Setting::find(1)->delivery_charge_per;
            foreach ($food as $value) {
                $value->driver_earning = $value->payment*$d_charge/100;
                $value->customer_name = User::findOrFail($value->customer_id)->name;
            }
            foreach ($grocery as $value)
            {
                $value->driver_earning = $value->payment*$d_charge/100;
                $value->customer_name = User::findOrFail($value->customer_id)->name;
            }
        }
        else
        {
            $request->validate([
                'start_date' => 'bail|required|date_format:Y-m-d',
                'end_date' => 'bail|required|date_format:Y-m-d',
            ]);
            $food = Order::where([['deliveryBoy_id',Auth::user()->id],['order_status','Delivered'],['payment_status',1]])
            ->whereBetween('date', [ $request->start_date,  $request->end_date])
            ->orderBy('id', 'DESC')->get();
            $grocery = GroceryOrder::where([['deliveryBoy_id',Auth::user()->id],['order_status','Delivered'],['payment_status',1]])
            ->whereBetween('date', [ $request->start_date,  $request->end_date])
            ->orderBy('id', 'DESC')->get();
            $d_charge = Setting::find(1)->delivery_charge_per;
            foreach ($food as $value)
            {
                $value->driver_earning = $value->payment*$d_charge/100;
                $value->customer_name = User::findOrFail($value->customer_id)->name;
            }
            foreach ($grocery as $value) {
                $value->driver_earning = $value->payment*$d_charge/100;
                $value->customer_name = User::findOrFail($value->customer_id)->name;
            }
        }
        $data = array_merge($food->toArray(), $grocery->toArray());
        return response()->json(['success'=>true,'msg'=>null ,'data' =>$data ], 200);
    }

    public function driverCancelOrder(Request $request){
        $request->validate([
            'order_id' => 'bail|required',
            'cancel_reason' => 'bail|required',
        ]);
        Order::findOrFail($request->order_id)->update(['order_status'=>'Cancel','deliveryBoy_id'=>Auth::user()->id,'cancel_reason'=>$request->cancel_reason]);
        $order = Order::findOrFail($request->order_id);
        return response()->json(['success'=>true,'msg'=>'order is successfully canceled' ,'data' =>$order ], 200);
    }

    public function trackOrder($id){
        $data = Order::with(['shop','orderItem'])->where('id',$id)->first();
        if($data->deliveryBoy_id!=null){
            $data->driver = User::findOrFail($data->deliveryBoy_id);
        }
        $data->imagePath = url('images/upload') . '/bike.png';
        return response()->json(['success'=>true,'msg'=>null ,'data' =>$data ], 200);
    }
    public function driverLocation($id)
    {
        $data = Order::findOrFail($id);
        if($data->deliveryBoy_id!=null){
            if($data->order_status=="Delivered" || $data->order_status=="Cancel"){
                return response()->json(['success'=>false,'msg'=>null ,'data' =>$data->order_status ], 200);
            }
            else{
                $detail = User::where('id',$data->deliveryBoy_id)->first(['id','name','lat','lang','role','image','phone']);
                $detail->order_status = $data->order_status;
                return response()->json(['success'=>true,'msg'=>null ,'data' =>$detail ], 200);
            }
        }
        else{
            return response()->json(['success'=>false,'msg'=>'driver not accepted' ,'data' =>null ], 200);
        }

    }

    public function viewnotification(){
        $data = Notification::where('driver_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        foreach ($data as $value) {
            if($value->notification_type == 'Food'){
                $order = Order::findOrFail($value->order_id);
                $value->shop_name = Shop::findOrFail($order->shop_id)->name;
            }
            else{
                $order = GroceryOrder::findOrFail($value->order_id);
                $value->shop_name = GroceryShop::findOrFail($order->shop_id)->name;
            }

        }
        return response()->json(['success'=>true,'msg'=>null ,'data' =>$data ], 200);
    }

    public function saveDriverLocation(Request $request){
        $request->validate( [
            'lat' => 'bail|required|numeric',
            'lang' => 'bail|required|numeric',
        ]);
        User::findOrFail(Auth::user()->id)->update(['lat'=>$request->lat,'lang'=>$request->lang]);
        $data = User::findOrFail(Auth::user()->id);

        return response()->json(['success'=>true,'msg'=>null ,'data' =>$data ], 200);
    }

    // public function viewDriverLocation($id){
    //     $order = Order::findOrFail($id);
    //     if($order->order_status=="Pending" || $order->order_status=="Approved" ){
    //         return response()->json(['success'=>false,'msg'=>'driver is not accepted' ,'data' =>null ], 200);
    //     }
    //     else if($order->order_status=="DriverApproved"){
    //         return response()->json(['success'=>false,'msg'=>'driver is not yet on his way' ,'data' =>null ], 200);
    //     }
    //     else{
    //         return response()->json(['success'=>true,'msg'=>null ,'data' =>$data ], 200);
    //     }
    // }


    public function accpetOrder($id){
         $order = Order::findOrFail($id)->update(['order_status'=>'Approved']);
        $msg =array(
            'icon'=>'fas fa-thumbs-up',
            'msg'=>'Order is Accepted Successfully',
            'heading'=>'Success',
            'type' => 'default'
        );

        return redirect()->back()->with('success',$msg);
    }

    public function rejectOrder($id){
        Order::findOrFail($id)->update(['order_status'=>'Cancel']);
        $order = Order::findOrFail($id);
        $user = User::findOrFail($order->customer_id);
        $notification = Setting::findOrFail(1);
        $shop_name = CompanySetting::where('id',1)->first()->name;
        $message = NotificationTemplate::where('title','Cancel Order')->first()->message_content;
        $detail['name'] = $user->name;
        $detail['order_no'] = $order->order_no;
        $detail['shop'] =Shop::findOrFail($order->shop_id)->name;
        $detail['shop_name'] = $shop_name;
        $data = ["{{name}}","{{order_no}}", "{{shop}}","{{shop_name}}"];
        $message1 = str_replace($data, $detail, $message);

        if($notification->push_notification ==1){
            if($user->enable_notification ==1){
            if($user->device_token!=null){
                Config::set('onesignal.app_id', env('APP_ID'));
                Config::set('onesignal.rest_api_key', env('REST_API_KEY'));
                Config::set('onesignal.user_auth_key', env('USER_AUTH_KEY'));

                $userId=$user->device_token;
                try{
                OneSignal::sendNotificationToUser(
                    $message1,
                    $userId,
                    $url = null,
                    $data = null,
                    $buttons = null,
                    $schedule = null
                );
            }catch (\Exception $e) {

            }
            }
            }
        }

        $image = NotificationTemplate::where('title','Cancel Order')->first()->image;
        $data1 = array();
        $data1['user_id']= $order->customer_id;
        $data1['order_id']= $order->id;
        $data1['title']= 'Order Canceled';
        $data1['message']= $message1;
        $data1['image'] = $image;
        $data1['notification_type'] = "Food";
        Notification::create($data1);

        $msg =array(
            'icon'=>'fas fa-thumbs-up',
            'msg'=>'Order is Cancel Successfully',
            'heading'=>'Success',
            'type' => 'default'
        );

        return redirect()->back()->with('success',$msg);
    }

    public function customerLoyaltyReport(){
        $shop = Shop::orderBy('id', 'DESC')->get();
        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
        $data = UserPoint::orderBy('id', 'DESC')->get();
        foreach ($data as $value) {
            $value->userName = User::find($value->user_id)->name;
            $value->shopName = Shop::find($value->shop_id)->name;
        }
        return view('mainAdmin.point.customerPointReport',['data'=>$data,'currency'=>$currency,'shops'=>$shop]);
    }

    public function viewUserPointLog($user_id,$shop_id){
        $log = PointLog::where([['user_id',$user_id],['shop_id',$shop_id]])->orderBy('id', 'DESC')->get();
        foreach ($log as $value) {
            $value->order = Order::find($value->order_id);
        }
        $user = User::find($user_id);
        $user->total_point = UserPoint::where([['user_id',$user_id],['shop_id',$shop_id]])->first()->total_point;
        $user->use_point = UserPoint::where([['user_id',$user_id],['shop_id',$shop_id]])->first()->use_point;
        $user->total_spent = UserPoint::where([['user_id',$user_id],['shop_id',$shop_id]])->first()->total_spent;
        $shop = Shop::find($shop_id);
        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
        return view('mainAdmin.point.pointLog',['log'=>$log,'currency'=>$currency,'user'=>$user,'shop'=>$shop]);
    }

    public function pointLogFilter(Request $request){

        if($request->reportPeriod==null && $request->period_day !=null){
            $date = Carbon::parse($request->period_day);
            $data =  PointLog::where([['user_id',$request->user_id],['shop_id',$request->shop_id]])
            ->whereBetween('created_at', [ $date->format('Y-m-d'). " 00:00:00",  $date->format('Y-m-d') . " 23:59:59"])
            ->orderBy('id', 'DESC')
            ->get();
        }
        if($request->reportPeriod==null && $request->period_day ==null){
            $data = PointLog::where([['user_id',$request->user_id],['shop_id',$request->shop_id]])->orderBy('id', 'DESC')->get();
        }
        if($request->reportPeriod=="month"){
            $day = Carbon::parse(Carbon::now()->year.'-'.$request->period_month.'-01')->daysInMonth;
            $data = PointLog::where([['user_id',$request->user_id],['shop_id',$request->shop_id]])
                ->whereBetween('created_at', [ Carbon::now()->year."-".$request->period_month."-01 00:00:00",  Carbon::now()->year."-".$request->period_month."-".$day . " 23:59:59"])
                ->orderBy('id', 'DESC')
                ->get();
        }
        elseif($request->reportPeriod=="year"){
            $data = PointLog::where([['user_id',$request->user_id],['shop_id',$request->shop_id]])
                ->whereBetween('created_at', [ $request->period_year."-01-01 00:00:00",  $request->period_year."-12-31 23:59:59"])
                ->orderBy('id', 'DESC')
                ->get();
        }
        elseif($request->reportPeriod=="week"){
            $date = Carbon::parse($request->period_week);
            $data = PointLog::where([['user_id',$request->user_id],['shop_id',$request->shop_id]])
            ->whereBetween('created_at', [ $date->startOfWeek()->format('Y-m-d'). " 00:00:00",  $date->endOfWeek()->format('Y-m-d') . " 23:59:59"])
                ->orderBy('id', 'DESC')
                ->get();
        }
        elseif($request->reportPeriod=="day"){
            $date = Carbon::parse($request->period_day);
            $data =  PointLog::where([['user_id',$request->user_id],['shop_id',$request->shop_id]])
            ->whereBetween('created_at', [ $date->format('Y-m-d'). " 00:00:00",  $date->format('Y-m-d') . " 23:59:59"])
            ->orderBy('id', 'DESC')
            ->get();
        }
        foreach ($data as $value) {
            $value->order = Order::find($value->order_id);
        }
        $user = User::find($request->user_id);
        $user->total_point = UserPoint::where([['user_id',$request->user_id],['shop_id',$request->shop_id]])->first()->total_point;
        $user->use_point = UserPoint::where([['user_id',$request->user_id],['shop_id',$request->shop_id]])->first()->use_point;
        $user->total_spent = UserPoint::where([['user_id',$request->user_id],['shop_id',$request->shop_id]])->first()->total_spent;
        $shop = Shop::find($request->shop_id);
        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
        return view('mainAdmin.point.pointLog',['log'=>$data,'currency'=>$currency,'user'=>$user,'shop'=>$shop]);


    }
}




