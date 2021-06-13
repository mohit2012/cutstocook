<?php

namespace App\Http\Controllers;

use App\GroceryOrder;
use App\GroceryOrderChild;
use App\Setting;
use App\User;
use App\GroceryShop;
use Auth;
use App\CompanySetting;
use App\Notification;
use OneSignal;
use App\NotificationTemplate;
use App\Currency;
use App\Config;
use Illuminate\Http\Request;

class GroceryOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        //
        $data = GroceryOrder::with(['shop','customer','deliveryGuy'])->where('owner_id',Auth::user()->id)->orderBy('id', 'DESC')->paginate(10);
        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
        return view('admin.GroceryOrder.orders',['orders'=>$data,'currency'=>$currency]);
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
     * @param  \App\GroceryOrder  $groceryOrder
     * @return \Illuminate\Http\Response
     */
    public function show(GroceryOrder $groceryOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GroceryOrder  $groceryOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(GroceryOrder $groceryOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GroceryOrder  $groceryOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GroceryOrder $groceryOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GroceryOrder  $groceryOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroceryOrder $groceryOrder)
    {
        //
    }

    public function viewsingleOrder($id){
        $data = GroceryOrder::with(['shop','customer','deliveryGuy','orderItem'])->find($id);

        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
      
        return view('admin.GroceryOrder.singleOrder',['data'=>$data,'currency'=>$currency]);
    }

    public function orderInvoice($id){
        $data = GroceryOrder::with(['shop','customer','deliveryGuy','orderItem'])->find($id);
        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
        return view('admin.GroceryOrder.invoice',['data'=>$data,'currency'=>$currency]);
    }

    public function printGroceryInvoice($id){
        $data = GroceryOrder::with(['shop','customer','deliveryGuy','orderItem'])->find($id);
        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
        return view('admin.GroceryOrder.invoicePrint',['data'=>$data,'currency'=>$currency]);
    }

    public function groceryRevenueReport(){
        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
        $data = GroceryOrder::with(['shop','customer'])->where([['payment_status',1],['owner_id',Auth::user()->id]])->orderBy('id', 'DESC')->get();
        // $data = GroceryOrder::with(['shop','customer'])->where('owner_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        $shops = GroceryShop::where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        foreach ($data as  $value) {
            $value->shop_name = GroceryShop::where('id',$value->shop_id)->first()->name;
        }
        return view('admin.GroceryOrder.revenueReport',['data'=>$data,'currency'=>$currency,'shops'=>$shops]);
    }

    public function accpetOrder($id){
        
        $order = GroceryOrder::findOrFail($id)->update(['order_status'=>'Approved']);
       $msg =array(
           'icon'=>'fas fa-thumbs-up',
           'msg'=>'Order is Accepted Successfully',
           'heading'=>'Success',
           'type' => 'default'
       );

       return redirect()->back()->with('success',$msg);
   }

   
   public function rejectOrder($id){
    
        GroceryOrder::findOrFail($id)->update(['order_status'=>'Cancel']);
        $order = GroceryOrder::findOrFail($id);
        $user = User::findOrFail($order->customer_id);
        $notification = Setting::findOrFail(1);
        $shop_name = CompanySetting::where('id',1)->first()->name;
        $message = NotificationTemplate::where('title','Cancel Order')->first()->message_content;
        $detail['name'] = $user->name;
        $detail['order_no'] = $order->order_no;
        $detail['shop'] =GroceryShop::findOrFail($order->shop_id)->name;
        $detail['shop_name'] = $shop_name;
        $data = ["{{name}}","{{order_no}}", "{{shop}}","{{shop_name}}"];
        $message1 = str_replace($data, $detail, $message);

        if($notification->push_notification ==1){
            if($user->enable_notification ==1){
            if($user->device_token!=null){
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
                } catch (\Throwable $th) {
                    // throw $th;
                }
            }
            }
        }

        $image = NotificationTemplate::where('title','Cancel Order')->first()->image;
        $data1 = array();
        $data1['user_id']= $order->customer_id;
        $data1['order_id']= $order->id;
        $data1['title']= 'Grocery Order Canceled';
        $data1['message']= $message1;
        $data1['image'] = $image;
        $data1['notification_type'] = "Grocery";

        Notification::create($data1);

        $msg =array(
            'icon'=>'fas fa-thumbs-up',
            'msg'=>'Order is Cancel Successfully',
            'heading'=>'Success',
            'type' => 'default'
        );

        return redirect()->back()->with('success',$msg);
    }

    public function changeGroceryOrderStatus(Request $request){

        
        $order = GroceryOrder::findOrFail($request->id);
        $status = $request->status;
        // if($order->payment_status==0 && $status=="Delivered"){
        //     GroceryOrder::find($request->id)->update(['payment_status'=>1]); 
        //     // return response()->json(['data' =>$order ,'success'=>false  ], 200);
        // }
        // else{
            GroceryOrder::findOrFail($request->id)->update(['order_status'=>$request->status]);
            $order = GroceryOrder::findOrFail($request->id);
            $user = User::findOrFail($order->customer_id);
            if($status=='Cancel' || $status=='Approved' || $status=='Delivered' || $status =="OrderReady")
            {
                if( $status=='Delivered'){
                    GroceryOrder::find($request->id)->update(['payment_status'=>1]);     
                }
                $notification = Setting::findOrFail(1);
                $shop_name = CompanySetting::where('id',1)->first()->name;
                $content = NotificationTemplate::where('title','Order Status')->first()->mail_content;
                $message = NotificationTemplate::where('title','Order Status')->first()->message_content;
                $detail['name'] = $user->name;
                $detail['order_no'] = $order->order_no;
                $detail['shop'] =GroceryShop::findOrFail($order->shop_id)->name;
                $detail['status'] =$status;
                $detail['shop_name'] = $shop_name;
                if($notification->mail_notification==1){
                    // Mail::to($user)->send(new OrderStatus($content,$detail));
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
                    } catch (\Throwable $th) {
                        // throw $th;
                    }
                    }
                }

                if($order->deliveryBoy_id !=null){
                    $driver = User::findOrFail($order->deliveryBoy_id);
                    $driverMessage = NotificationTemplate::where('title','Order Status')->first()->message_content;
                    $driverDetail['name'] = $driver->name;
                    $driverDetail['order_no'] = $order->order_no;
                    $driverDetail['shop'] =GroceryShop::findOrFail($order->shop_id)->name;
                    $driverDetail['status'] =$status;
                    $driverDetail['shop_name'] = $shop_name;
                    if($notification->push_notification ==1){
                        if($driver->device_token!=null){
                            try{
                            Config::set('onesignal.app_id', env('APP_ID'));
                            Config::set('onesignal.rest_api_key', env('REST_API_KEY'));
                            Config::set('onesignal.user_auth_key', env('USER_AUTH_KEY'));
    
                            $driverData = ["{{name}}", "{{order_no}}", "{{shop}}","{{status}}", "{{shop_name}}"];
                            $driverMessage1 = str_replace($driverData, $driverDetail, $driverMessage);
                            $device_token=$driver->device_token;
                            OneSignal::sendNotificationToUser(
                                $driverMessage1,
                                $device_token,
                                $url = null,
                                $data = null,
                                $buttons = null,
                                $schedule = null
                            );
                            } catch (\Throwable $th) {
                                // throw $th;
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
                    $driverData1['notification_type'] = "Grocery";
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
                $data1['notification_type'] = "Grocery";
                Notification::create($data1);
            }

            return response()->json(['data' =>$order ,'success'=>true], 200);
        // }

    }

}
