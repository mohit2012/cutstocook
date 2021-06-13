<?php

namespace App\Http\Controllers;

use App\Order;
use App\Shop;
use App\User;
use App\GroceryOrder;
use App\Setting;
use App\CompanySetting;
use App\Currency;
use App\GroceryShop;
use App\NotificationTemplate;
use App\Notification;
use App\AdminNotification;
use Carbon\Carbon;
use Auth;
use Config;
use OneSignal;
use Illuminate\Http\Request;

class OwnerApiController extends Controller{

    public function ownerLogin(Request $request){
        $request->validate([
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6',
            'device_token' => 'bail|required',
        ]);
        $data = $request->all();
        $userdata = array(
            'email'     => $request->email,
            'password'  => $request->password,
            'role' => 1,
        );
        if(Auth::attempt($userdata)){
            $user = Auth::user();
            User::find(Auth::user()->id)->update(['device_token'=>$data['device_token']]);
            $user['token'] = $user->createToken('Foodeli')->accessToken;
            return response()->json(['success'=>true ,'msg' =>null,'data' =>$user ], 200);
        }
        else{
            return response()->json(['success'=>false ,'msg' =>'Invalid email or password','data' =>null ], 200);
        }
    }

    public function ownerDashboard(){
        $data['today_order'] = Order::where([['owner_id',Auth::user()->id],['date',Carbon::now()->format('Y-m-d')]])->count();
        $data['total_order'] = Order::where('owner_id',Auth::user()->id)->count();
        $data['shops'] = Shop::where('user_id',Auth::user()->id)->count();
        $data['uses'] = User::where('role',0)->count();
        $data['sales'] = Order::where([['owner_id',Auth::user()->id],['payment_status',1]])->sum('payment');
        return response()->json(['success'=>true ,'msg' =>null,'data' =>$data ], 200);
    }

    public function viewOrders(){
        $grocery['all'] = GroceryOrder::with(['shop','customer','deliveryGuy'])->where('owner_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        $grocery['pending'] = GroceryOrder::with(['shop','customer','deliveryGuy'])->where([['owner_id',Auth::user()->id],['order_status','Pending']])->orderBy('id', 'DESC')->get();
        $grocery['approve'] = GroceryOrder::with(['shop','customer','deliveryGuy'])
        ->where([['owner_id',Auth::user()->id],['order_status','Approved']])
        ->orWhere([['owner_id',Auth::user()->id],['order_status','DriverApproved']])
        ->orderBy('id', 'DESC')->get();
        $grocery['onTheWay'] = GroceryOrder::with(['shop','customer','deliveryGuy'])
        ->where([['owner_id',Auth::user()->id],['order_status','OrderReady']])
        ->orWhere([['owner_id',Auth::user()->id],['order_status','PickUpGrocery']])
        ->orWhere([['owner_id',Auth::user()->id],['order_status','OutOfDelivery']])
        ->orWhere([['owner_id',Auth::user()->id],['order_status','DriverReach']])
        ->orderBy('id', 'DESC')->get();
        $grocery['complete'] = GroceryOrder::with(['shop','customer','deliveryGuy'])->where([['owner_id',Auth::user()->id],['order_status','Delivered']])->orderBy('id', 'DESC')->get();
        $grocery['cancel'] = GroceryOrder::with(['shop','customer','deliveryGuy'])->where([['owner_id',Auth::user()->id],['order_status','Cancel']])->orderBy('id', 'DESC')->get();
        $data['all'] = Order::where('owner_id',Auth::user()->id)->with(['location','shop','customer','deliveryGuy','orderItem'])->orderBy('id', 'DESC')->get();
        $data['pending'] = Order::where([['owner_id',Auth::user()->id],['order_status','Pending']])->with(['location','shop','customer','deliveryGuy','orderItem'])->orderBy('id', 'DESC')->get();
        $data['approve'] = Order::where([['owner_id',Auth::user()->id],['order_status','Approved']])
        ->orWhere([['owner_id',Auth::user()->id],['order_status','DriverApproved']])
        ->with(['location','shop','customer','deliveryGuy','orderItem'])
        ->orderBy('id', 'DESC')->get();
        $data['onTheWay'] = Order::where([['owner_id',Auth::user()->id],['order_status','Prepare']])
        ->orWhere([['owner_id',Auth::user()->id],['order_status','DriverAtShop']])
        ->orWhere([['owner_id',Auth::user()->id],['order_status','PickUpFood']])
        ->orWhere([['owner_id',Auth::user()->id],['order_status','OnTheWay']])
        ->orWhere([['owner_id',Auth::user()->id],['order_status','DriverReach']])
        ->with(['location','shop','customer','deliveryGuy','orderItem'])
        ->orderBy('id', 'DESC')->get();
        $data['complete'] = Order::where([['owner_id',Auth::user()->id],['order_status','Delivered']])->with(['location','shop','customer','deliveryGuy','orderItem'])->orderBy('id', 'DESC')->get();
        $data['cancel'] = Order::where([['owner_id',Auth::user()->id],['order_status','Cancel']])->with(['location','shop','customer','deliveryGuy','orderItem'])->orderBy('id', 'DESC')->get();
        $master['grocery'] = $grocery;
        $master['food'] = $data;
        return response()->json(['success'=>true ,'msg' =>null,'data' =>$master ], 200);
    }

    public function singleOrder($id){
        $data = Order::with(['location','shop','customer','deliveryGuy','orderItem'])->find($id);
        return response()->json(['success'=>true ,'msg' =>null,'data' =>$data ], 200);
    }

    public function grocerySingleOrder($id){
        $data = GroceryOrder::with(['shop','customer','deliveryGuy'])->find($id);
        return response()->json(['success'=>true ,'msg' =>null,'data' =>$data ], 200);
    }

    public function notification(){
        $data = AdminNotification::where('owner_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        return response()->json(['success'=>true ,'msg' =>null,'data' =>$data ], 200);
    }

    public function changeGroceryStatus(Request $request){
        $request->validate([
            'order_id' => 'bail|required',
            'status' => 'bail|required',
        ]);
        GroceryOrder::find($request->order_id)->update(['order_status'=>$request->status]);
        $order = GroceryOrder::find($request->order_id);
        $user = User::find($order->customer_id);
        $notification = Setting::find(1);
        $shop_name = CompanySetting::where('id',1)->first()->name;
        $message = NotificationTemplate::where('title','Order Status')->first()->message_content;
        $detail['name'] = $user->name;
        $detail['order_no'] = $order->order_no;
        $detail['shop'] =GroceryShop::find($order->shop_id)->name;
        $detail['status'] =$request->status;
        $detail['shop_name'] = $shop_name;
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

            $data = ["{{name}}", "{{order_no}}", "{{shop}}","{{status}}", "{{shop_name}}"];
            $message1 = str_replace($data, $detail, $message);
            $image = NotificationTemplate::where('title','Order Status')->first()->image;

            $data1 = array();
            $data1['user_id']= $order->customer_id;
            $data1['order_id']= $order->id;
            $data1['title']= 'Order '.$request->status;
            $data1['message']= $message1;
            $data1['image'] = $image;
            $data1['notification_type'] = "Grocery";
            Notification::create($data1);
        }

        return response()->json(['data' =>'status Changed' ,'success'=>true], 200);
    }

    public function changeStatus(Request $request){
        $request->validate([
            'order_id' => 'bail|required',
            'status' => 'bail|required',
        ]);
        Order::find($request->order_id)->update(['order_status'=>$request->status]);
        $order = Order::find($request->order_id);
        $user = User::find($order->customer_id);
        $notification = Setting::find(1);
        $shop_name = CompanySetting::where('id',1)->first()->name;
        $message = NotificationTemplate::where('title','Order Status')->first()->message_content;
        $detail['name'] = $user->name;
        $detail['order_no'] = $order->order_no;
        $detail['shop'] =Shop::find($order->shop_id)->name;
        $detail['status'] =$request->status;
        $detail['shop_name'] = $shop_name;
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

            $data = ["{{name}}", "{{order_no}}", "{{shop}}","{{status}}", "{{shop_name}}"];
            $message1 = str_replace($data, $detail, $message);
            $image = NotificationTemplate::where('title','Order Status')->first()->image;

            $data1 = array();
            $data1['user_id']= $order->customer_id;
            $data1['order_id']= $order->id;
            $data1['title']= 'Order '.$request->status;
            $data1['message']= $message1;
            $data1['image'] = $image;
            $data1['notification_type'] = "Food";
            Notification::create($data1);
        }

        return response()->json(['data' =>'status Changed' ,'success'=>true], 200);

    }

    public function shops(){
        $shop = Shop::where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        return response()->json(['data' =>$shop[0] ,'msg'=>null,'success'=>true], 200);
    }

    public function earning(){
        $data['today'] =  Order::where([['owner_id',Auth::user()->id],['date',Carbon::now()->format('Y-m-d')],['payment_status',1]])->sum('payment');
        $data['month'] =  Order::where([['owner_id',Auth::user()->id],['payment_status',1]])
        ->whereDate('date', '>', Carbon::now()->subDays(30))->sum('payment');
        $data['total'] =  Order::where([['owner_id',Auth::user()->id],['payment_status',1]])->sum('payment');
        return response()->json(['data' =>$data ,'msg'=>null,'success'=>true], 200);
    }

    public function ownerSetting(){
        $key = Setting::where('id',1)->first(['onesignal_app_id','onesignal_project_number','currency','map_key']);
        $key['currency_symbol'] =  Currency::where('code',$key->currency)->first()->symbol;
        $key['logo'] = CompanySetting::find(1)->logo;
        $key['imagePath'] = url('images/upload') . '/';
        $key['app_name'] = CompanySetting::find(1)->name;
        return response()->json(['data' =>$key ,'msg'=>null,'success'=>true], 200);
    }

    public function clearNotification(){
        $data = AdminNotification::where('owner_id',Auth::user()->id)>get();
        foreach ($data as $value) {
            $value->delete();
        }
        return response()->json(['success'=>true ,'msg' =>null,'data' =>'Notification deleted' ], 200);
    }
}

?>
