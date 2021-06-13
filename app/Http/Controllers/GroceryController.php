<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GroceryCategory;
use App\GroceryShop;
use App\GroceryReview;
use App\GroceryItem;
use App\PaymentSetting;
use App\OwnerSetting;
use App\NotificationTemplate;
use App\Coupon;
use Twilio\Rest\Client;
use App\UserAddress;
use App\CompanySetting;
use App\Notification;
use App\User;
use OneSignal;
// use Paytabs;
use DB;
use App\AdminNotification;
use App\Setting;
use Config;
use App\GroceryOrder;
use Auth;
use App\Mail\OrderStatus;
use App\Mail\OrderCreate;
use Illuminate\Support\Facades\Mail;
use App\GrocerySubCategory;
use App\GroceryOrderChild;
use Carbon\Carbon;

class GroceryController extends Controller
{
    //

    public function groceryCategory(){
        $category = GroceryCategory::where('status',0)->orderBy('id', 'DESC')->get();
        return response()->json(['success'=>true ,'data' =>$category ], 200);
    }

    public function grocerySubCategory($id){
        $subcategory = GrocerySubCategory::where([['category_id',$id],['status',0]])->orderBy('id', 'DESC')->get();
        foreach ($subcategory as $value) {
            $value->items = GroceryItem::where([['subcategory_id',$value->id],['status',0]])->orderBy('id', 'DESC')->get();
        }
        return response()->json(['success'=>true ,'data' =>$subcategory ], 200);
    }

    public function groceryShop(){

        $master = array();
        $master['shop'] = GroceryShop::where('status',0)->orderBy('id', 'DESC')->get();
        $master['coupon'] =Coupon::where('status',0)->orderBy('id', 'DESC')->get();
        return response()->json(['success'=>true ,'data' =>$master ], 200);
    }

    public function groceryShopDetail($id){
        $shop = GroceryShop::where('status',0)->find($id);
        return response()->json(['success'=>true ,'data' =>$shop ], 200);
    }

    public function groceryItem($id){
        $item = GroceryItem::where([['shop_id',$id],['status',0]])->orderBy('id', 'DESC')->get();
        return response()->json(['success'=>true ,'data' =>$item ], 200);
    }

    public function groceryItemDetail($id){
        $item = GroceryItem::where('status',0)->find($id);
        return response()->json(['success'=>true ,'data' =>$item ], 200);
    }

    public function addGroceryReview(Request $request){
        $request->validate([
            'order_id' => 'bail|required',
            'customer_id' => 'bail|required',
            'shop_id' => 'bail|required',
            // 'deliveryBoy_id' => 'bail|required',
            'message' => 'bail|required',
            'rate' => 'bail|required',
        ]);
        $data = $request->all();
        if(isset($data['deliveryBoy_id']))
        {
            $data['deliveryBoy_id'] = $data['deliveryBoy_id'];
        }
        else
        {
            $data['deliveryBoy_id'] = GroceryOrder::find($request->order_id)->deliveryBoy_id;
        }
        $review = GroceryReview::create($data);
        $child = GroceryOrderChild::where('order_id',$request->order_id)->get();
        foreach ($child as $value){
            $data1['order_id'] = $request->order_id;
            $data1['customer_id'] = $request->customer_id;
            $data1['message'] = $request->message;
            $data1['rate'] = $request->rate;
            $data1['item_id'] = $value->item_id;
            $review1 = GroceryReview::create($data1);
        }
        GroceryOrder::findOrFail($request->order_id)->update(['review_status'=>1]);
        return response()->json(['msg' => null, 'data' => $review,'success'=>true], 200);
    }


    public function createGroceryOrder(Request $request){
        $request->validate( [
            'shop_id' => 'bail|required',
            'payment' => 'bail|required',
            'delivery_charge' => 'bail|required',
            'items' => 'bail|required',
            'itemData' => 'bail|required',
            'payment_status' => 'bail|required',
            'payment_type' => 'bail|required',
            'payment_token' => 'required_if:payment_type,STRIPE,PAYPAL,RAZOR',
        ]);
        $data = $request->all();
        $data['customer_id'] = Auth::user()->id;
        if(isset($request->coupon_id)){
            $count = Coupon::findOrFail($request->coupon_id)->use_count;
            $count = $count +1;
            Coupon::findOrFail($request->coupon_id)->update(['use_count'=>$count]);
        }
        $data['owner_id'] = GroceryShop::findOrFail($request->shop_id)->user_id;
        $data['order_status'] = OwnerSetting::where('user_id',$data['owner_id'])->first()->default_grocery_order_status;
        $data['order_no'] = '#' . rand(100000, 999999);
        $data['order_otp'] = rand(1000, 9999);
        $data['time'] = Carbon::now('Asia/Kolkata')->format('h:i A');
        $data['address_id'] = User::find(Auth::user()->id)->address_id;
        $data['date'] = Carbon::now()->format('Y-m-d');

        $order = GroceryOrder::create($data);
        foreach (json_decode($data['itemData']) as $value){
            $child['order_id'] = $order->id;
            $child['item_id'] = $value->item_id;
            $child['price'] = $value->price;
            $child['quantity'] = $value->quantity;
            GroceryOrderChild::create($child);
        }

        // user notification
        $user = User::findOrFail($order->customer_id);
        $notification = Setting::findOrFail(1);
        $shop_name = CompanySetting::where('id',1)->first()->name;
        $content = NotificationTemplate::where('title','Create Order')->first()->mail_content;
        $message = NotificationTemplate::where('title','Create Order')->first()->message_content;
        $detail['name'] = $user->name;
        $detail['shop'] =GroceryShop::findOrFail($order->shop_id)->name;
        $detail['shop_name'] = $shop_name;
        if($notification->mail_notification==1){
            try {
                // Mail::to($user)->send(new OrderCreate($content,$detail));
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        if($notification->push_notification ==1){
            if($user->device_token!=null  && env('APP_ID')!=null){
                Config::set('onesignal.app_id', env('APP_ID'));
                Config::set('onesignal.rest_api_key', env('REST_API_KEY'));
                Config::set('onesignal.user_auth_key', env('USER_AUTH_KEY'));
                $data = ["{{name}}", "{{shop}}","{{shop_name}}"];
                $message1 = str_replace($data, $detail, $message);
                $userId=$user->device_token;
                try {
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
        $data = ["{{name}}", "{{shop}}","{{shop_name}}"];
        $message1 = str_replace($data, $detail, $message);
        $image = NotificationTemplate::where('title','Create Order')->first()->image;
        $data1 = array();
        $data1['user_id']= $order->customer_id;
        $data1['order_id']= $order->id;
        $data1['title']= 'Order Created';
        $data1['message']= $message1;
        $data1['image'] = $image;
        $data1['notification_type'] = "Grocery";
        Notification::create($data1);

        // owner notification
        $owner_id = GroceryShop::where('id',$order->shop_id)->first()->user_id;
        $web_notification = OwnerSetting::where('user_id',$owner_id)->first()->web_notification;
        $message_noti = NotificationTemplate::where('title','Order Arrive')->first()->message_content;
        $shop = GroceryShop::findOrFail($order->shop_id)->name;
        $shop_name = CompanySetting::findOrFail(1)->name;
        $detail1['name'] = User::findOrFail($owner_id)->name;
        $detail1['order_no'] = $order->order_no;
        $detail1['shop'] =$shop;
        $detail1['customer_name'] = $user->name;
        $detail1['shop_name'] = $shop_name;
        $data1 = ["{{name}}", "{{order_no}}", "{{shop}}", "{{customer_name}}", "{{shop_name}}"];
        $message1 = str_replace($data1, $detail1, $message_noti);
        $admin_web_notiA = Setting::find(1)->web_notification;

        if($web_notification ==1 && $admin_web_notiA == 1){
            $userId = User::findOrFail($owner_id)->device_token;
            if($userId!=null  && env('APP_ID_WEB')!=null){
                Config::set('onesignal.app_id', env('APP_ID_WEB'));
                Config::set('onesignal.rest_api_key', env('REST_API_KEY_WEB'));
                Config::set('onesignal.user_auth_key', env('USER_AUTH_KEY_WEB'));
                 $url = url('owner/viewGroceryOrder/'.$order->id.$order->order_no);
                try {
                    OneSignal::sendNotificationToUser(
                        $message1,
                        $userId,
                        $url = $url,
                        $data = null,
                        $buttons = null,
                        $schedule = null
                    );
                } catch (\Throwable $th) {
                    // throw $th;
                }

            }
        }
        $data_noti['owner_id'] =  User::findOrFail($owner_id)->id;
        $data_noti['user_id'] =  $order->customer_id;
        $data_noti['order_id'] =  $order->id;
        $data_noti['message'] =   $message1;

        // driver notification
        $message_noti = NotificationTemplate::where('title','Order Request')->first()->message_content;
        $drivers = User::where([['role',2],['driver_available',1]])->get();
        foreach ($drivers as $driver){
            $lat= $driver->lat;
            $lng= $driver->lang;
            $radius = $driver->driver_radius;
            $shopResult = DB::select(DB::raw('SELECT id,radius, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians(latitude) ) ) ) AS distance FROM  grocery_shop  WHERE id = '. $order->shop_id .'   HAVING distance < '.$radius.'  ORDER BY distance'));
            $userResult = DB::select(DB::raw('SELECT id, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lang ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians(lat) ) ) ) AS distance FROM users WHERE id = '. $order->customer_id .'  HAVING distance < '.$radius.'  ORDER BY distance'));

            if(count($shopResult)>0 && count($userResult)>0 ){
                $customer = User::findOrFail($order->customer_id);
                $address = UserAddress::findOrFail($customer->address_id);
                $driverDetail['name'] = $driver->name;
                $driverDetail['order_no'] = $order->order_no;
                $driverDetail['user_address'] =$address->soc_name.' ,'.$address->street.' ,'.$address->city;
                $driverDetail['shop'] =GroceryShop::findOrFail($order->shop_id)->name;
                $driverDetail['shop_name'] = CompanySetting::findOrFail(1)->name;
                $driverData = ["{{name}}", "{{order_no}}", "{{user_address}}", "{{shop}}", "{{shop_name}}"];
                $driverMessage = str_replace($driverData, $driverDetail, $message_noti);
                $userId=$driver->device_token;
                if($userId!=null && env('APP_ID')!= null){
                    Config::set('onesignal.app_id', env('APP_ID'));
                    Config::set('onesignal.rest_api_key', env('REST_API_KEY'));
                    Config::set('onesignal.user_auth_key', env('USER_AUTH_KEY'));
                    try {
                        OneSignal::sendNotificationToUser(
                            $driverMessage,
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

                $image = NotificationTemplate::where('title','Order Request')->first()->image;
                $driverData1 = array();
                $driverData1['driver_id']= $driver->id;
                $driverData1['order_id']= $order->id;
                $driverData1['title']= 'Order Request';
                $driverData1['message']= $driverMessage;
                $driverData1['image'] = $image;
                $driverData1['notification_type'] = "Grocery";
                Notification::create($driverData1);
            }

        }


        $data = GroceryOrder::find($order->id);
        return response()->json(['msg' => null, 'data' => $data,'success'=>true], 200);
    }

    public function groceryOrder(){
        $master = array();
        $master['past'] = GroceryOrder::with(['shop','customer','deliveryGuy'])
        ->where([['customer_id',Auth::user()->id],['order_status','Delivered']])
        ->orWhere([['customer_id',Auth::user()->id],['order_status','Cancel']])
        ->orderBy('id', 'DESC')->get();

        $master['current'] = GroceryOrder::with(['shop','customer','deliveryGuy'])
        ->where([['customer_id',Auth::user()->id],['order_status','Pending']])
        ->orWhere([['customer_id',Auth::user()->id],['order_status','Approved']])
        ->orWhere([['customer_id',Auth::user()->id],['order_status','OrderReady']])
        ->orWhere([['customer_id',Auth::user()->id],['order_status','OutOfDelivery']])
        ->orWhere([['customer_id',Auth::user()->id],['order_status','DriverReach']])
        ->orWhere([['customer_id',Auth::user()->id],['order_status','DriverApproved']])
        ->orWhere([['customer_id',Auth::user()->id],['order_status','PickUpGrocery']])
        ->orderBy('id', 'DESC')->get();

        return response()->json(['msg' => null, 'data' => $master,'success'=>true], 200);
    }

    public function groceryShopCategory($id){
        $category = GroceryShop::find($id)->category_id;
        $category = GroceryCategory::where('status',0)->whereIn('id',explode(",",$category))->orderBy('id', 'DESC')->get();
        return response()->json(['data' =>$category ,'success'=>true], 200);
    }

    public function trackOrder($id){
        $data = GroceryOrder::with(['shop','customer','deliveryGuy'])->find($id);

        $data->imagePath = url('images/upload') . '/bike.png';
        return response()->json(['success'=>true,'msg'=>null ,'data' =>$data ], 200);
    }


    public function singleGroceryOrder($id){
        $data = GroceryOrder::with(['shop','customer','deliveryGuy'])->find($id);
        $data['review'] =  GroceryReview::where([['order_id',$data->id],['shop_id',$data->shop_id]])->first();
        return response()->json(['msg' => null, 'data' => $data,'success'=>true], 200);
    }


    public function groceryCategoryShop($id){
        $shop = GroceryShop::where('status',0)->get();
        $shops = array();
        foreach ($shop as $value) {
            $likes=array_filter(explode(',',$value->category_id));
            if(count(array_keys($likes,$id))>0){
                if (($key = array_search($id, $likes)) !== false) {
                    array_push($shops,$value->id);
                }
            }
        }
        $data = GroceryShop::where('status',0)->whereIn('id',$shops)->orderBy('id', 'DESC')->get();
        return response()->json(['success'=>true,'msg'=>null ,'data' =>$data ], 200);
    }

    public function deliveredProduct(Request $request){
        $request->validate([
            'order_id' => 'bail|required',
            'order_otp' => 'bail|required',
        ]);

        $order = GroceryOrder::where('id',$request->order_id)->first();
        if($order->order_otp == $request->order_otp){
            GroceryOrder::findOrFail($order->id)->update(['order_status'=>'Delivered','payment_status'=>1]);
            $a = GroceryOrder::findOrFail($order->id);
            return response()->json(['success'=>true,'msg'=>null ,'data' =>$a ], 200);
        }
        else{
            return response()->json(['success'=>false,'msg'=>'Incorrect OTP!' ,'data' =>null ], 200);
        }
    }



    public function groceryOrderRequest(){
        $auth = Auth::user();
        $master = array();
        $radiusUser = array();
        $shop = array();
        $date = Carbon::now()->format('Y-m-d');
        $lat= $auth->lat;
        $lng= $auth->lang;
        $radius = $auth->driver_radius;
        $results = DB::select(DB::raw('SELECT id,radius, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians(latitude) ) ) ) AS distance FROM grocery_shop HAVING distance < '.$radius.'  ORDER BY distance'));
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
        $pending = GroceryOrder::with(['shop'])->orWhereIn('customer_id',$radiusUser)->whereIn('shop_id',$shop)->where([['order_status','Pending'],['delivery_type','delivery']])
        ->whereDate('date',$date)->orderBy('id', 'DESC')->get();
        foreach ($pending as $value) {
            if(in_array($auth->id, explode(',',$value->reject_by))==false){
                array_push($pending_order,$value->id);
            }
        }
        $pending = GroceryOrder::with(['shop'])->whereIn('id',$pending_order)->orderBy('id', 'DESC')->get();
        foreach ($pending as $value) {
            $user= User::findOrFail($value->customer_id);
            $value->delivery_address =  UserAddress::find($value->address_id);
        }

        $approved = GroceryOrder::with(['shop'])->orWhereIn('customer_id',$radiusUser)->whereIn('shop_id',$shop)->where([['order_status','Approved'],['delivery_type','delivery']])
        ->whereDate('date',$date)->orderBy('id', 'DESC')->get();
        foreach ($approved as $value) {
            if(in_array($auth->id, explode(',',$value->reject_by))==false){
                array_push($approve_order,$value->id);
            }
        }
        $approved = GroceryOrder::with(['shop'])->whereIn('id',$approve_order)->orderBy('id', 'DESC')->get();
        foreach ($approved as $value) {
            $user= User::findOrFail($value->customer_id);
            $value->delivery_address =  UserAddress::find($value->address_id);
        }
        $master['requests'] = array_merge($pending ->toArray(), $approved->toArray());
        $master['accepted'] = GroceryOrder::with(['shop'])->where([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','DriverApproved']])
        ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','OrderReady']])
        ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','DriverAtShop']])
        ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','PickUpGrocery']])
        ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','OutOfDelivery']])
        ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','DriverReach']])
        ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','Delivered'],['payment_status',0]])
        ->orderBy('id', 'DESC')->get();

        foreach ($master['accepted'] as $value) {
            $user= User::findOrFail($value->customer_id);
            $value->delivery_address = UserAddress::find($value->address_id);
        }

        if(Auth::user()->driver_radius==null){
            return response()->json(['success'=>true,'msg'=>null ,'data' =>[] ], 200);
        }
        else
        {
            if(Auth::user()->driver_available==1){
                return response()->json(['success'=>true,'msg'=>null ,'data' =>$master ], 200);
            }
            else{
                return response()->json(['success'=>true,'msg'=>null ,'data' =>[] ], 200);
            }
        }
    }

    public function acceptGroceryOrderRequest(Request $request){
        $request->validate([
            'order_id' => 'bail|required',
        ]);
        $driver_id = Auth::user()->id;
        $order = GroceryOrder::findOrFail($request->order_id);
        if($order->order_status=="Approved"){
            $date = Carbon::now()->format('Y-m-d');

            $accepted = GroceryOrder::where([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','DriverApproved']])
            ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','OrderReady']])
            ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','DriverAtShop']])
            ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','PickUpGrocery']])
            ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','OutOfDelivery']])
            ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','DriverReach']])
            ->orWhere([['date',$date],['deliveryBoy_id',Auth::user()->id],['order_status','Delivered'],['payment_status',0]])
            ->get();

            if(count($accepted)==0){
                GroceryOrder::findOrFail($order->id)->update(['deliveryBoy_id'=>$driver_id,'order_status'=>'DriverApproved']);
                $data = GroceryOrder::findOrFail($order->id);
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

    public function driverGroceryStatus(Request $request){

        $request->validate( [
            'status' => 'bail|required',
            'order_id' => 'bail|required',
        ]);

        GroceryOrder::findOrFail($request->order_id)->update(['order_status'=>$request->status]);

        $a = GroceryOrder::findOrFail($request->order_id);
        $a->shop = GroceryShop::findOrFail($a->shop_id);
        $a->customer = User::findOrFail($a->customer_id);
        return response()->json(['success'=>true,'msg'=>'status Updated' ,'data' =>$a ], 200);
    }

    public function collectGroceryPayment($id){
        GroceryOrder::findOrFail($id)->update(['payment_status'=>1]);
        $a = GroceryOrder::findOrFail($id);
        $a->shop = GroceryShop::findOrFail($a->shop_id);
        $a->customer = User::findOrFail($a->customer_id);
        return response()->json(['success'=>true,'msg'=>'Payment Collected successfully!' ,'data' =>$a ], 200);
    }

    public function driverCancelGroceryOrder(Request $request){
        $request->validate( [
            'order_id' => 'bail|required',
        ]);
        GroceryOrder::find($request->order_id)->update(['order_status'=>'Cancel']);
        $order = GroceryOrder::find($request->order_id);
        $user = User::findOrFail($order->customer_id);
        $notification = Setting::findOrFail(1);
        $shop_name = CompanySetting::where('id',1)->first()->name;
        $message = NotificationTemplate::where('title','Order Status')->first()->message_content;
        $detail['name'] = $user->name;
        $detail['order_no'] = $order->order_no;
        $detail['shop'] =GroceryShop::findOrFail($order->shop_id)->name;
        $detail['status'] = 'Cancel';
        $detail['shop_name'] = $shop_name;
        if($notification->push_notification ==1){
            if($user->device_token!=null  && env('APP_ID')!=null){
                Config::set('onesignal.app_id', env('APP_ID'));
                Config::set('onesignal.rest_api_key', env('REST_API_KEY'));
                Config::set('onesignal.user_auth_key', env('USER_AUTH_KEY'));
                $data = ["{{name}}", "{{order_no}}", "{{shop}}","{{status}}", "{{shop_name}}"];
                $message1 = str_replace($data, $detail, $message);
                $userId=$user->device_token;
                try {
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
        $data = ["{{name}}", "{{order_no}}", "{{shop}}","{{status}}", "{{shop_name}}"];
        $message1 = str_replace($data, $detail, $message);
        $image = NotificationTemplate::where('title','Order Status')->first()->image;

        $data1 = array();
        $data1['user_id']= $order->customer_id;
        $data1['order_id']= $order->id;
        $data1['title']= 'Order Cancel';
        $data1['message']= $message1;
        $data1['image'] = $image;
        $data1['notification_type'] = "Grocery";
        Notification::create($data1);

        return response()->json(['success'=>true,'msg'=>'order is successfully canceled' ,'data' =>$order ], 200);
    }

    public function pickupGrocery(Request $request){
        $request->validate([
            'order_id' => 'bail|required',
            'otp' => 'bail|required',
        ]);
        $order = GroceryOrder::where('id',$request->order_id)->first();
        if($order->order_otp == $request->otp){
            GroceryOrder::findOrFail($order->id)->update(['order_status'=>'PickUpGrocery']);
            $a = GroceryOrder::findOrFail($order->id);
            return response()->json(['success'=>true,'msg'=>null ,'data' =>$a ], 200);
        }
        else{
            return response()->json(['success'=>false,'msg'=>'Incorrect OTP!' ,'data' =>null ], 200);
        }

    }

    public function viewGroceryOrderDetail($id){
        $a = GroceryOrder::find($id);
        $a->shop = GroceryShop::find($a->shop_id);
        $a->customer = User::find($a->customer_id);
        $a->driver = User::find($a->deliveryBoy_id,['lat','lang','role','id','image']);
        return response()->json(['success'=>true,'msg'=>null ,'data' =>$a ], 200);
    }

    public function driverCancelOrder(Request $request){
        $request->validate( [
            'order_id' => 'bail|required',
        ]);
        GroceryOrder::find($request->order_id)->update(['order_status'=>'Cancel']);
        $order = GroceryOrder::find($request->order_id);
        $user = User::find($order->customer_id);
        $notification = Setting::findOrFail(1);
        $shop_name = CompanySetting::where('id',1)->first()->name;

        $content = NotificationTemplate::where('title','Order Status')->first()->mail_content;
        $message = NotificationTemplate::where('title','Order Status')->first()->message_content;
        $detail['name'] = $user->name;
        $detail['order_no'] = $order->order_no;
        $detail['shop'] =GroceryShop::findOrFail($order->shop_id)->name;
        $detail['status'] =$status;
        $detail['shop_name'] = $shop_name;

        if($notification->push_notification ==1){
            if($user->device_token!=null && env('APP_ID')!=null){
                Config::set('onesignal.app_id', env('APP_ID'));
                Config::set('onesignal.rest_api_key', env('REST_API_KEY'));
                Config::set('onesignal.user_auth_key', env('USER_AUTH_KEY'));
                $data = ["{{name}}", "{{order_no}}", "{{shop}}","{{status}}", "{{shop_name}}"];
                $message1 = str_replace($data, $detail, $message);
                $userId=$user->device_token;
                try {
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


        return response()->json(['success'=>true,'msg'=>'order is successfully canceled' ,'data' =>$order ], 200);
    }

    // rejectGroceryOrder

    public function rejectGroceryOrder($id){
        $order = GroceryOrder::find($id);
        $likes=array_filter(explode(',',$order->reject_by));
        if(count(array_keys($likes,Auth::user()->id))>0){
        }
        else{
            array_push($likes,Auth::user()->id);
        }
        $driver =implode(',',$likes);
        $order= GroceryOrder::find($id);
        $order->reject_by =$driver;
        $order->update();
        $a= GroceryOrder::find($id);
        return response()->json(['msg' => null ,'data' =>$a, 'success'=>true], 200);
    }



}
