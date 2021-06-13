<?php

namespace App\Http\Controllers;

use App\User;
use App\Shop;
use App\Admin;
use App\Category;
use App\UserAddress;
use App\Setting;
use App\AdminNotification;
use App\CompanySetting;
use App\Item;
use App\NotificationTemplate;
use App\UserGallery;
use App\Order;
use App\OwnerSetting;
use App\Currency;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Avatar;
use Auth;
use Zip;
use DB;
use App;
use App\ChatRoom;
use App\ChatMsg;
use Redirect;
use Twilio\Rest\Client;
use App\Mail\UserVerification;
use App\Mail\ForgetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Notification;
use App\Review;
use App\Package;
use App\Coupon;
use App\Gallery;
use App\OrderChild;
use App\GroceryOrder;
use App\GroceryShop;
use App\GroceryOrderChild;
use App\GroceryReview;
use App\GrocerySubCategory;
use App\GroceryItem;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = User::where('role',0)->orderBy('id', 'DESC')->get();
        return view('mainAdmin.users.users',['users'=>$data]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('mainAdmin.users.addUser');
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

        $request->validate([
            'name' => 'bail|required',
            'email' => 'bail|required|unique:users',
            'phone' => 'bail|required|numeric',
            'role' => 'bail|required',
            // 'dateOfBirth' => 'bail|required',
            'password' => 'bail|required|min:6',
            'password_confirmation' => 'bail|required|same:password|min:6'
        ]);
        $data = $request->all();
        $data['password']=Hash::make($data['password']);
        $data['referral_code'] = mt_rand(1000000,9999999);
        $data['cover_image'] = 'NoPath - Copy (89).png';
        $data['otp'] = 123456;
        // $data['otp'] = mt_rand(100000,999999);

        if (isset($request->image) && $request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }
        else{
            // $image = Avatar::create($request->email)->toBase64();
            // $image = str_replace('data:image/png;base64,', '', $image);
            // $image = str_replace(' ', '+', $image);
            // $imageName = str_random(10).'.'.'png';
            // $destinationPath = public_path('/images/upload');
            // \File::put($destinationPath. '/' . $imageName, base64_decode($image));
            $data['image']='user.png';
        }

        $user_verify = Setting::where('id',1)->first()->user_verify;
        if($user_verify==1){
            $data['verify'] = 0;
        }
        else if($user_verify==0){
            $data['verify'] = 1;
        }

        $user = User::create($data);
        if($user->role==1){
            $setting['user_id'] = $user->id;
            OwnerSetting::create($setting);
            return redirect('mainAdmin/shopOwners');
        }
        else{
            return redirect('mainAdmin/Customer');
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $data = User::findOrFail($id);

        return view('mainAdmin.users.editUser',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'name' => 'bail|required',
            'email' => 'bail|required|unique:users,email,' . $id . ',id',
            'phone' => 'bail|required|numeric',
            // 'dateOfBirth' => 'bail|required',
        ]);
        $data = $request->all();

        if (isset($request->image) && $request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }

        User::findOrFail($id)->update($data);
        return redirect('mainAdmin/Customer');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
         try {
            $user = User::findOrFail($id);
            if($user->role==0){
                $Order = Order::where('customer_id',$id)->get();
                if(count($Order)>0){
                    foreach ($Order as $value) {
                        $OrderChild = OrderChild::where('order_id',$value->id)->get();
                        if(count($OrderChild)>0){
                            foreach ($OrderChild as $oc) {
                                $oc->delete();
                            }
                        }
                        $AdminNotification = AdminNotification::where('order_id',$value->id)->get();
                        if(count($AdminNotification)>0){
                            foreach ($AdminNotification as $an) {
                                $an->delete();
                            }
                        }
                        $value->delete();
                    }
                }
                $Order = GroceryOrder::where('customer_id',$id)->get();
                if(count($Order)>0){
                    foreach ($Order as $value) {
                        $OrderChild = GroceryOrderChild::where('order_id',$value->id)->get();
                        if(count($OrderChild)>0){
                            foreach ($OrderChild as $oc) {
                                $oc->delete();
                            }
                        }
                        $value->delete();
                    }
                }

                $Review = Review::where('customer_id',$id)->get();
                if(count($Review)>0){
                    foreach ($Review as $r) {
                        $r->delete();
                    }
                }
                $GroceryReview = GroceryReview::where('customer_id',$id)->get();
                if(count($GroceryReview)>0){
                    foreach ($GroceryReview as $r) {
                        $r->delete();
                    }
                }
                $Notification = Notification::where('user_id',$id)->get();
                if(count($Notification)>0){
                    foreach ($Notification as $n) {
                        $n->delete();
                    }
                }
                $UserAddress = UserAddress::where('user_id',$id)->get();
                if(count($UserAddress)>0){
                    foreach ($UserAddress as $n) {
                        $n->delete();
                    }
                }
                $UserGallery = UserGallery::where('user_id',$id)->get();
                if(count($UserGallery)>0){
                    foreach ($UserGallery as $g) {
                        $g->delete();
                    }
                }
            }
            if($user->role==2){
                $Order = Order::where('deliveryBoy_id',$id)
                ->whereIn('order_status',['DriverApproved','Prepare','DriverAtShop','PickUpFood','OnTheWay','DriverReach'])
                ->get();
                if(count($Order)>0){
                    return response('Data is Connected with other Data', 400);
                }
                $GroceryOrder = GroceryOrder::where('deliveryBoy_id',$id)
                ->whereIn('order_status',['DriverApproved','PickUpGrocery','OutOfDelivery','DriverReach'])
                ->get();
                if(count($GroceryOrder)>0){
                    return response('Data is Connected with other Data', 400);
                }
            }
            if($user->role==1){
                $Shops = Shop::where('user_id',$id)->get();
                if(count($Shops)>0){
                    foreach ($Shops as $shop) {
                        $Item = Item::where('shop_id',$shop->id)->get();
                        if(count($Item)>0){
                            foreach ($Item as $value) {
                                $value->delete();
                            }
                        }
                        $Package = Package::where('shop_id',$shop->id)->get();
                        if(count($Package)>0){
                            foreach ($Package as $value) {
                                $value->delete();
                            }
                        }

                        $Coupon = Coupon::where([['shop_id',$shop->id],'use_for'=>'Food'])->get();
                        if(count($Coupon)>0){
                            foreach ($Coupon as $value) {
                                $value->delete();
                            }
                        }
                        $Gallery = Gallery::where('shop_id',$shop->id)->get();
                        if(count($Gallery)>0){
                            foreach ($Gallery as $value) {
                                $value->delete();
                            }
                        }

                        $Order = Order::where('shop_id',$shop->id)->get();
                        if(count($Order)>0){
                            foreach ($Order as $value) {
                                $Notification = Notification::where([['order_id',$value->id],['notification_type','Food']])->get();
                                if(count($Notification)>0){
                                    foreach ($Notification as $n) {
                                        $n->delete();
                                    }
                                }
                                $Review = Review::where('order_id',$value->id)->get();
                                if(count($Review)>0){
                                    foreach ($Review as $r) {
                                        $r->delete();
                                    }
                                }
                                $OrderChild = OrderChild::where('order_id',$value->id)->get();
                                if(count($OrderChild)>0){
                                    foreach ($OrderChild as $oc) {
                                        $oc->delete();
                                    }
                                }
                                $AdminNotification = AdminNotification::where('order_id',$value->id)->get();
                                if(count($AdminNotification)>0){
                                    foreach ($AdminNotification as $an) {
                                        $an->delete();
                                    }
                                }
                                $value->delete();
                            }
                        }
                        $shop->delete();
                    }
                }
                $GroceryItem = GroceryItem::where('user_id',$id)->get();
                if(count($GroceryItem)>0){
                    foreach ($GroceryItem as $value) {
                        $value->delete();
                    }
                }
                $GrocerySubCategory = GrocerySubCategory::where('owner_id',$id)->get();
                if(count($GrocerySubCategory)>0){
                    foreach ($GrocerySubCategory as $value) {
                        $value->delete();
                    }
                }


                $GroceryOrder = GroceryOrder::where('owner_id',$id)->get();
                if(count($GroceryOrder)>0){
                    foreach ($GroceryOrder as $value) {
                        $Notification = Notification::where([['order_id',$value->id],['notification_type','Grocery']])->get();
                        if(count($Notification)>0){
                            foreach ($Notification as $n) {
                                $n->delete();
                            }
                        }
                        $GroceryReview = GroceryReview::where('order_id',$value->id)->get();
                        if(count($GroceryReview)>0){
                            foreach ($GroceryReview as $r) {
                                $r->delete();
                            }
                        }
                        $GroceryOrderChild = GroceryOrderChild::where('order_id',$value->id)->get();
                        if(count($GroceryOrderChild)>0){
                            foreach ($GroceryOrderChild as $oc) {
                                $oc->delete();
                            }
                        }
                        $value->delete();
                    }
                }

                $gShops = GroceryShop::where('user_id',$id)->get();
                if(count($gShops)>0){
                    foreach ($gShops as $gShop) {
                        $Coupon = Coupon::where([['shop_id',$gShop->id],'use_for'=>'Grocery'])->get();
                        if(count($Coupon)>0){
                            foreach ($Coupon as $value) {
                                $value->delete();
                            }
                        }

                        $gShop->delete();
                    }
                }
            }

            $user->delete();
            return 'true';
        } catch (\Exception $e) {
            return response('Data is Connected with other Data', 400);
        }

    }

    public function addDeliveryBoy(){

        return view('mainAdmin.users.addDriver');
    }

    public function addDriver(Request $request){
        $request->validate([
            'name' => 'bail|required',
            'email' => 'bail|required|unique:users',
            'phone' => 'bail|required|numeric',
            'lat' => 'bail|required|numeric',
            'lang' => 'bail|required|numeric',
            // 'driver_radius' => 'bail|required',
            'password' => 'bail|required|min:6',
            'password_confirmation' => 'bail|required|same:password|min:6'
        ]);
        $data = $request->all();
        $data['role']=2;
        $data['driver_available'] = 1;
        $data['password']=Hash::make($data['password']);
        if (isset($request->image) && $request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }
        else{
            // $image = Avatar::create($request->email)->toBase64();
            // $image = str_replace('data:image/png;base64,', '', $image);
            // $image = str_replace(' ', '+', $image);
            // $imageName = str_random(10).'.'.'png';
            // $destinationPath = public_path('/images/upload');
            // \File::put($destinationPath. '/' . $imageName, base64_decode($image));
            $data['image']='user.png';
        }
        $data['otp'] = 123456;
        // $data['otp'] = mt_rand(100000,999999);

        $data['driver_radius']  = Setting::where('id',1)->first()->default_driver_radius;
        $user_verify = Setting::where('id',1)->first()->user_verify;
        if($user_verify==1){
            $data['verify'] = 0;
        }
        else if($user_verify==0){
            $data['verify'] = 1;
        }
        $user = User::create($data);
        return redirect('mainAdmin/deliveryGuys');

    }

    public function editDriver($id){
        $data = User::findOrFail($id);
        return view('mainAdmin.users.editDriver',['data'=>$data]);
    }

    public function assignRadius(Request $request){
        User::findOrFail($request->driver_id)->update(['driver_radius'=>$request->driver_radius]);
        return back();
    }

    public function updateDriver(Request $request,$id){
        $request->validate([
            'name' => 'bail|required',
            'email' => 'bail|required|unique:users,email,' . $id . ',id',
            'phone' => 'bail|required|numeric',
            'driver_radius' => 'bail|required',
        ]);
        $data = $request->all();
        if (isset($request->image) && $request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }

        User::findOrFail($id)->update($data);
        return redirect('mainAdmin/deliveryGuys');
    }


    public function viewUsers(){
        $data = User::where('role',0)->orderBy('id', 'DESC')->get();
        return view('admin.users.users',['users'=>$data]);
    }

    public function shopOwners(){
        $users = User::where('role',1)->orderBy('id', 'DESC')->get();
        return view('mainAdmin.users.owners',['users'=>$users]);
    }

    public function deliveryGuys(){
        $users = User::where('role',2)->orderBy('id', 'DESC')->get();
        return view('mainAdmin.users.deliveryGuys',['users'=>$users]);
    }

    public function adminProfileform(){
        $master = array();
        $master['shops'] = Shop::get()->count();
        $master['users'] = User::where('role',0)->get()->count();
        $master['deliveryBoy'] = User::where('role',2)->get()->count();
        return view('mainAdmin.profile',['master'=>$master]);
    }
    public function editAdminProfile(Request $request){
       $id = Auth::guard('mainAdmin')->user()->id;
        $request->validate([
            'name' => 'bail|required',
            'email' => 'bail|required|unique:admin,email,' . $id . ',id',
        ]);
        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }

        Admin::findOrFail($id)->update($data);
        return redirect('mainAdmin/adminProfile');
    }

    public function changeAdminPassword(Request $request){

        $request->validate([
            'old_password' => 'bail|required',
            'password' => 'bail|required|min:6',
            'password_confirmation' => 'bail|required|same:password|min:6'
        ]);

        if (Hash::check($request->old_password, Auth::guard('mainAdmin')->user()->password)){
            Admin::findOrFail(Auth::guard('mainAdmin')->user()->id)->update(['password'=>Hash::make($request->password)]);
            return back();
            // return response()->json(['success'=>true,'msg'=>'Your password is change successfully','data' =>null ], 200);
        }
        else{
            return Redirect::back()->with('error_msg','Current Password is wrong!');
        }
    }

    public function ownerProfileform(){
        $master = array();
        $master['shops'] = Shop::where('user_id',Auth::user()->id)->get()->count();
        $master['users'] = User::where('role',0)->get()->count();
        // $master['deliveryBoy'] = User::where('role',1)->get();
        return view('admin.ownerProfile',['master'=>$master]);
    }

    public function editOwnerProfile(Request $request){
        $id = Auth::user()->id;
        $request->validate([
            'name' => 'bail|required',
            'email' => 'bail|required|unique:users,email,' . $id . ',id',
        ]);
        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }

        User::findOrFail($id)->update($data);
        return redirect('owner/ownerProfile');
    }
    public function changeOwnerPassword(Request $request){
        $request->validate([
            'old_password' => 'bail|required',
            'password' => 'bail|required|min:6',
            'password_confirmation' => 'bail|required|same:password|min:6'
        ]);

        if (Hash::check($request->old_password, Auth::user()->password)){
            User::findOrFail(Auth::guard('mainAdmin')->user()->id)->update(['password'=>Hash::make($request->password)]);
            return back();
            // return response()->json(['success'=>true,'msg'=>'Your password is change successfully','data' =>null ], 200);
        }
        else{
            return Redirect::back()->with('error_msg','Current Password is wrong!');
        }
    }

    public function ResetPassword(){
        return view('auth.passwords.reset');
    }

    public function reset_password(Request $request)
    {
        $user = User::where([['email',$request->email],['role',1]])->first();
        $password=rand(100000,999999);
        if($user){
            $content = NotificationTemplate::where('title','Forget Password')->first()->mail_content;
            $detail['name'] = $user->name;
            $detail['password'] = $password;
            $detail['shop_name'] = CompanySetting::where('id',1)->first()->name;
            try{
            Mail::to($user)->send(new ForgetPassword($content,$detail));
            } catch (\Throwable $th) {
                // throw $th;
            }
            User::findOrFail($user->id)->update(['password'=>Hash::make($password)]);
            return Redirect::back()->with('success_msg','Please check your email new password will send on it.');
        }
        return Redirect::back()->with('error_msg','Invalid Email ID');
    }

    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => 'bail|required_if:provider,GOOGLE,LOCAL|email',
            'password' => 'bail|required_if:provider,LOCAL|min:6',
            'provider' => 'bail|required',
            'name' => 'bail|required_if:provider,GOOGLE,FACEBOOK',
            'image' => 'bail|required_if:provider,GOOGLE,FACEBOOK',
            'provider_token' => 'bail|required_if:provider:GOOGLE,FACEBOOK',
        ]);

        $data = $request->all();
        if($data['provider']=='LOCAL'){
            $userdata = array(
                'email'     => $request->email,
                'password'  => $request->password,
                'role' => 0,
                'status' => 0,
            );
            if(Auth::attempt($userdata))
            {
                $user_verify = Setting::where('id',1)->first()->user_verify;
                $user = Auth::user();
                if($user_verify==1){
                    if(Auth::user()->verify == 1)
                    {
                        if(isset($data['device_token']))
                        {
                            User::findOrFail(Auth::user()->id)->update(['device_token'=>$data['device_token']]);
                        }
                        $user['token'] = $user->createToken('Foodeli')->accessToken;
                        return response()->json(['msg' => null, 'data' => $user,'success'=>true], 200);
                    }
                    else{
                        return response()->json(['msg' => 'Please Verify Your Phone number.', 'data' => $user,'success'=>false], 200);
                    }
                }
                else if($user_verify==0)
                {
                    $user['token'] = $user->createToken('Foodeli')->accessToken;
                    return response()->json(['msg' => null, 'data' => $user,'success'=>true], 200);
                }
            }
            else{

                return response()->json(['msg' => 'Invalid Username or password', 'data' => null,'success'=>false], 400);
            }
        }
        else
        {
            $data = $request->all();
            $data['role'] = 0;
            $filtered = Arr::except($data, ['provider_token']);
            if ($data['provider'] !== 'LOCAL') {
                $email = User::where('email', $data['email'])->first();
                if ($email)
                {
                    $email->provider_token = $request->provider_token;
                    $token = $email->createToken('Foodeli')->accessToken;
                    $email->save();
                    $email['token'] = $token;
                    return response()->json(['msg' => 'login successfully', 'data' => $email, 'success' => true], 200);
                }
                else
                {
                    $data = User::firstOrCreate(['provider_token' => $request->provider_token], $filtered);
                    if ($request->image != null)
                    {
                        $url = $request->image;
                        $contents = file_get_contents($url);
                        $name = substr($url, strrpos($url, '/') + 1);
                        $destinationPath = public_path('/images/upload/') . $name . '.png';
                        file_put_contents($destinationPath, $contents);
                        $data['image'] = $name . '.png';
                    }
                    if (isset($data['device_token'])) {
                        $data['device_token'] = $data->device_token;
                    }
                    $data->save();
                    $token = $data->createToken('Foodeli')->accessToken;
                    $data['token'] = $token;
                    return response()->json(['msg' => 'login successfully', 'data' => $data, 'success' => true], 200);
                }
            }
        }
    }

    public function driverLogin(Request $request){
        $request->validate([
            // 'email' => 'bail|required|email',
            // 'password' => 'bail|required|min:6',
            // 'provider' => 'bail|required',
            // 'device_token' => 'bail|required'
            'email' => 'bail|required_if:provider,GOOGLE,LOCAL|email',
            'password' => 'bail|required_if:provider,LOCAL|min:6',
            'provider' => 'bail|required',
            'name' => 'bail|required_if:provider,GOOGLE,FACEBOOK',
            'image' => 'bail|required_if:provider,GOOGLE,FACEBOOK',
            'provider_token' => 'bail|required_if:provider:GOOGLE,FACEBOOK',
        ]);

        $data = $request->all();
        if($data['provider']=='LOCAL')
        {
            $userdata = array(
                'email'     => $request->email,
                'password'  => $request->password,
                'role' => 2,
                'status' => 0,
            );
            if(Auth::attempt($userdata))
            {
                $user = Auth::user()->makeHidden(['shops']);
                if(Auth::user()->verify == 1)
                {
                    if(isset($data['device_token']))
                    {
                        User::findOrFail(Auth::user()->id)->update(['device_token'=>$data['device_token']]);
                    }
                    $user['token'] = $user->createToken('Foodeli')->accessToken;
                    return response()->json(['msg' => 'user login successfully..!!', 'data' => $user,'success'=>true], 200);
                }
                else{
                    return response()->json(['msg' => 'Please Verify Your Phone number.', 'data' => $user,'success'=>false], 200);
                }
            }
            else{

                return response()->json(['msg' => 'Invalid Username or password', 'data' => null,'success'=>false], 400);
            }
        }
        else
        {
            $data = $request->all();
            $data['role'] = 2;
            $filtered = Arr::except($data, ['provider_token']);
            if ($data['provider'] !== 'LOCAL') {
                $email = User::where('email', $data['email'])->first();
                if ($email)
                {
                    $email->provider_token = $request->provider_token;
                    $token = $email->createToken('Foodeli')->accessToken;
                    $email->save();
                    $email['token'] = $token;
                    return response()->json(['msg' => 'login successfully', 'data' => $email, 'success' => true], 200);
                }
                else
                {
                    $data = User::firstOrCreate(['provider_token' => $request->provider_token], $filtered);
                    if ($request->image != null)
                    {
                        $url = $request->image;
                        $contents = file_get_contents($url);
                        $name = substr($url, strrpos($url, '/') + 1);
                        $destinationPath = public_path('/images/upload/') . $name . '.png';
                        file_put_contents($destinationPath, $contents);
                        $data['image'] = $name . '.png';
                    }
                    if (isset($data['device_token'])) {
                        $data['device_token'] = $data->device_token;
                    }
                    $data->save();
                    $token = $data->createToken('Foodeli')->accessToken;
                    $data['token'] = $token;
                    return response()->json(['msg' => 'login successfully', 'data' => $data, 'success' => true], 200);
                }
            }
        }
    }

    public function userRegister(Request $request){
        $request->validate([
            'name' => 'bail|required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
            'password_confirmation' => 'bail|required|same:password|min:6'
        ]);
        $data = $request->all();
        $data['referral_code'] = mt_rand(1000000,9999999);
        $data['password']=Hash::make($data['password']);
        $data['cover_image'] = 'NoPath - Copy (89).png';
        $data['image'] = 'user.png';
        $data['otp'] = mt_rand(100000,999999);
        if(isset($request->friend_code))
        {
            $user = User::where([['referral_code',$request->friend_code],['referral_user',0],['verify',1]])->first();
            if($user)
            {
                $data['friend_code'] = $request->friend_code;
                User::findOrFail($user->id)->update(['referral_user'=>1]);
            }
            else
            {
                return response()->json(['msg' => 'This code is not available', 'data' => null,'success'=>false], 200);
            }
        }
        $user_verify = Setting::where('id',1)->first()->user_verify;
        if($user_verify==1){
            $data['verify'] = 0;
        }
        else if($user_verify==0)
        {
            $data['verify'] = 1;
        }
        $data1 = User::create($data);
        if($user_verify==1)
        {
            return response()->json(['msg' => 'Register Successfully!', 'data' => $data1,'success'=>true], 200);
        }
        else if($user_verify==0)
        {
            $user = User::findOrFail($data1->id);
            $user['token'] = $user->createToken('Foodeli')->accessToken;
            return response()->json(['msg' => 'Register Successfully!', 'data' => $user,'success'=>true], 200);
        }
    }

    public function driverRegister(Request $request){
        $request->validate([
            'name' => 'bail|required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
            'lat' => 'bail|required|numeric',
            'lang' => 'bail|required|numeric',
            'password_confirmation' => 'bail|required|same:password|min:6',
            'phone' => 'bail|required|digits:10',
            'phone_code' => 'bail|required'
        ]);

        $data = $request->all();
        $data['referral_code'] = mt_rand(1000000,9999999);
        $data['password']=Hash::make($data['password']);
        $data['role']=2;
        $data['otp'] = mt_rand(100000,999999);
        $data['image'] = 'user.png';
        $data['driver_available'] = 1;
        $data['driver_radius'] = Setting::find(1)->default_driver_radius;
        $user_verify = Setting::where('id',1)->first()->user_verify;
        if($user_verify==1){
            $data['verify'] = 0;
        }
        else if($user_verify==0){
            $data['verify'] = 1;
        }
        $data1 = User::create($data);
        if($user_verify==1)
        {
            return response()->json(['msg' => 'Register Successfully please verify your account!', 'data' => $data1,'success'=>true], 200);
        }
        else if($user_verify==0)
        {
            $user = User::find($data1->id)->makeHidden(['shops']);
            $user['token'] = $user->createToken('Foodeli')->accessToken;
            return response()->json(['msg' => 'Register Successfully !', 'data' => $user,'success'=>true], 200);
        }
    }

    public function verifyPhone(Request $request)
    {
        $request->validate([
            'id' => 'bail|required',
            'phone_code' => 'bail|required',
            'phone' => 'bail|required|numeric|digits:10|unique:users',
        ]);

        $user = User::findOrFail($request->id);
        if($user)
        {
            $setting = Setting::where('id',1)->first(['id','twilio_account_id','twilio_auth_token','twilio_phone_number','phone_verify','email_verify']);
            $content = NotificationTemplate::where('title','User Verification')->first()->mail_content;
            $message = NotificationTemplate::where('title','User Verification')->first()->message_content;

            if(strlen($request->phone) == 10)
            {
            $otp = mt_rand(100000,999999);
            $detail['name'] = $user->name;
            $detail['otp'] = $otp;
            $detail['shop_name'] = CompanySetting::where('id',1)->first()->name;
            User::find($user->id)->update(['otp'=>$otp]);
            if($setting->phone_verify==1)
            {
                $data = ["{{name}}", "{{otp}}", "{{shop_name}}"];
                $message1 = str_replace($data, $detail, $message);

                $sid =$setting->twilio_account_id;
                $token = $setting->twilio_auth_token;
                $p = $user->phone_code.$user->phone;
                try{
                $client = new Client($sid, $token);
                $client->messages->create(
                   $p,
                    array(
                        'from' => $setting->twilio_phone_number,
                        'body' => $message1
                    )
                );
                } catch (\Throwable $th) {
                    // throw $th;
                }
            }
            if($setting->email_verify==1){
                try
                {
                    Mail::to($user)->send(new UserVerification($content,$detail));
                }
                catch (\Throwable $th)
                {
                    // throw $th;
                }
            }
            return response()->json(['msg' => 'OTP will send in your phone, plz check it!', 'data' => null,'success'=>true], 200);
            }
            else{
                return response()->json(['msg' => 'Enter Valid Phone number!', 'data' => null,'success'=>false], 200);
            }
        }
        else{
            return response()->json(['msg' => 'Invalid User ID.', 'data' => null,'success'=>false], 400);
        }
    }

    public function verifyDriverPhone(Request $request){
        $request->validate([
            'id' => 'bail|required|numeric',
            'phone_code' => 'bail|required',
            'phone' => 'bail|required|numeric|min:6|unique:users',
        ]);

        $user = User::findOrFail($request->id);
        if($user){
            $setting = Setting::where('id',1)->first(['id','twilio_account_id','twilio_auth_token','twilio_phone_number','phone_verify','email_verify']);
            $content = NotificationTemplate::where('title','User Verification')->first()->mail_content;
            $message = NotificationTemplate::where('title','User Verification')->first()->message_content;

            if(strlen($request->phone) == 10)
            {
            $otp = mt_rand(100000,999999);
            $detail['name'] = $user->name;
            $detail['otp'] = $otp;
            $detail['shop_name'] = CompanySetting::where('id',1)->first()->name;
            User::findOrFail($user->id)->update(['otp'=>$otp]);

            if($setting->phone_verify==1){
                $data = ["{{name}}", "{{otp}}", "{{shop_name}}"];
                $message1 = str_replace($data, $detail, $message);

                $sid =$setting->twilio_account_id;
                $token = $setting->twilio_auth_token;
                $p = $user->phone_code.$user->phone;
                try{
                $client = new Client($sid, $token);
                $client->messages->create(
                    $p,
                    array(
                        'from' => $setting->twilio_phone_number,
                        'body' => $message1
                    )
                );
                } catch (\Throwable $th) {
                    // throw $th;
                }
            }
            if($setting->email_verify==1){
                try{
                Mail::to($user)->send(new UserVerification($content,$detail));
                } catch (\Throwable $th) {
                    // throw $th;
                }
            }
            return response()->json(['msg' => 'OTP will send in your phone, plz check it!', 'data' => null,'success'=>true], 200);
            }
            else{
                return response()->json(['msg' => 'Enter Valid Phone number!', 'data' => null,'success'=>false], 200);
            }
        }
        else{
            return response()->json(['msg' => 'Invalid User ID.', 'data' => null,'success'=>false], 400);
        }
    }

    public function resendOTP(Request $request){
        $request->validate([
            'id' => 'bail|required',
            // 'phone_code' => 'bail|required',
            // 'phone' => 'bail|required|min:6|unique:users',
        ]);
        $user = User::findOrFail($request->id);
        if($user){
            $setting = Setting::where('id',1)->first(['id','twilio_account_id','twilio_auth_token','twilio_phone_number','email_verify','phone_verify']);
            $content = NotificationTemplate::where('title','User Verification')->first()->mail_content;
            $message = NotificationTemplate::where('title','User Verification')->first()->message_content;

            if(strlen($request->phone) == 10){
                $otp = mt_rand(100000,999999);
                $detail['name'] = $user->name;
                $detail['otp'] = $otp;
                $detail['shop_name'] = CompanySetting::where('id',1)->first()->name;
                User::findOrFail($user->id)->update(['otp'=>$otp]);
                if($setting->phone_verify==1){
                    $data = ["{{name}}", "{{otp}}", "{{shop_name}}"];
                    $message1 = str_replace($data, $detail, $message);

                    $sid =$setting->twilio_account_id;
                    $token = $setting->twilio_auth_token;
                    $p = $user->phone_code.$user->phone;
                    try{
                    $client = new Client($sid, $token);
                    $client->messages->create(
                        $p,
                        array(
                            'from' => $setting->twilio_phone_number,
                            'body' => $message1
                        )
                    );
                    } catch (\Throwable $th) {
                        // throw $th;
                    }
                }
                else if($setting->email_verify==1){
                    try{
                    Mail::to($user)->send(new UserVerification($content,$detail));
                    } catch (\Throwable $th) {
                        // throw $th;
                    }
                }

                return response()->json(['msg' => null, 'data' => 'OTP will send in your phone, plz check it....','success'=>true], 200);
                }
        }
        else{
            return response()->json(['msg' => 'Invalid User ID.', 'data' => null,'success'=>false], 400);
        }

    }

    public function checkOtp(Request $request){
        $request->validate([
            'id' => 'bail|required|numeric',
            'otp' => 'bail|required|numeric',
            'phone' => 'bail|required|numeric',
            'phone_code' => 'bail|required',
        ]);

        $user = User::where([['id',$request->id],['otp',$request->otp]])->first();
        if($user)
        {
            User::findOrFail($user->id)->update(['verify'=>1,'phone'=>$request->phone,'phone_code'=>$request->phone_code]);
            $user = User::findOrFail($user->id);
            $user['token'] = $user->createToken('Foodeli')->accessToken;
            return response()->json(['msg' => null, 'data' => $user,'success'=>true], 200);
        }
        else
        {
            return response()->json(['msg' => 'Invalid OTP code.', 'data' => null,'success'=>false], 400);
        }
    }

    public function checkDriverOtp(Request $request){
        $request->validate([
            'id' => 'bail|required|numeric',
            'otp' => 'bail|required|numeric',
        ]);

        $user = User::where([['id',$request->id],['otp',$request->otp]])->first();
        if($user)
        {
            User::findOrFail($user->id)->update(['verify'=>1]);
            $user = User::findOrFail($user->id);
            $user['token'] = $user->createToken('Foodeli')->accessToken;
            return response()->json(['msg' => 'successfully verify', 'data' => $user,'success'=>true], 200);
        }
        else{
            return response()->json(['msg' => 'Invalid OTP code.', 'data' => null,'success'=>false], 400);
        }
    }

    public function changeAvaliableStatus($status)
    {
        $id = Auth::user()->id;
        $data = User::find($id)->update(['driver_available'=>$status]);
        return response()->json([ 'data' =>null,'success'=>true], 200);
    }

    public function userEditProfile(Request $request){
        $id = Auth::user()->id;
        $request->validate([
            'name' => 'bail|required',
            'email' => 'bail|required|unique:users,email,' . $id . ',id',
            'phone' => 'bail|required|numeric|unique:users,phone,' . $id . ',id',
            'location' => 'bail|required',
        ]);
        // $data = $request->all();

        // if(isset($request->image))
        // {
        //     $img = $request->image;
        //     $img = str_replace('data:image/png;base64,', '', $img);
        //     $img = str_replace(' ', '+', $img);
        //     $data1 = base64_decode($img);
        //     $Iname = uniqid();
        //     $file = public_path('/images/upload/') . $Iname . ".png";
        //     $success = file_put_contents($file, $data1);
        //     $data['image']=$Iname . ".png";
        // }
        $data['name']= $request->name;
        $data['email']= $request->email;
        $data['phone']= $request->phone;
        $data['location']= $request->location;

        User::findOrFail($id)->update($data);
        $data = User::findOrFail($id);

        return response()->json(['msg' => null, 'data' => $data,'success'=>true], 200);
    }

    public function changeImage(Request $request){
        $id = Auth::user()->id;
        $request->validate([
            'image' => 'bail|required',
            'image_type' => 'bail|required',
        ]);

        if(isset($request->image))
        {
            $img = $request->image;
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data1 = base64_decode($img);
            $Iname = uniqid();
            $file = public_path('/images/upload/') . $Iname . ".png";
            $success = file_put_contents($file, $data1);
            $image=$Iname . ".png";
        }
        if($request->image_type=="profile"){
            User::findOrFail($id)->update(['image'=>$image]);
        }
        else if($request->image_type=="cover"){
            User::findOrFail($id)->update(['cover_image'=>$image]);
        }
        $user = User::findOrFail($id);
        return response()->json(['msg' => null, 'data' => $user,'success'=>true], 200);

    }

    public function changeUserPassword(Request $request){
        $request->validate( [
            // 'oldPassword' => 'bail|required',
            'password' => 'bail|required|min:6',
            'confirmPassword' => 'bail|required|same:password|min:6'
        ]);
        // if (Hash::check($request->oldPassword, Auth::user()->password)){
        //     User::findOrFail(Auth::user()->id)->update(['password'=>Hash::make($request->password)]);
        //     return response()->json(['success'=>true,'msg'=>'Your password is change successfully','data' =>null ], 200);
        // }
        // else{
        //     return response()->json(['success'=>false,'msg'=>'Old Password is wrong!','data' =>null ], 400);
        // }
        User::findOrFail(Auth::user()->id)->update(['password'=>Hash::make($request->password)]);
         return response()->json(['success'=>true,'msg'=>'Your password is change successfully','data' =>null ], 200);
    }

    public function forgerUserPassword(Request $request){
        $request->validate([
            'email' => 'bail|required|email',
        ]);

        $user = User::where('email',$request->email)->first();
        if($user){
            $password = mt_rand(100000,999999);
            $content = NotificationTemplate::where('title','Forget Password')->first()->mail_content;
            $detail['name'] = $user->name;
            $detail['password'] = $password;
            $detail['shop_name'] = CompanySetting::where('id',1)->first()->name;
            try {
            Mail::to($user)->send(new ForgetPassword($content,$detail));
            } catch (\Throwable $th) {
                // throw $th;
            }
            $password=Hash::make($password);
            User::findOrFail($user->id)->update(['password'=>$password]);
            return response()->json(['success'=>true,'msg'=>'new password is Send in your mail.','data' =>null ], 200);
        }
        else{
            return response()->json(['success'=>false,'msg'=>'Invalid Email ID','data' =>null ], 400);
        }
    }

    public function addUserBookmark(Request $request){
        $request->validate( [
            'shop_id' => 'bail|required',
        ]);
        $users = User::findOrFail(Auth::user()->id);
        $likes=array_filter(explode(',',$users->favourite));
        if(count(array_keys($likes,$request->shop_id))>0){
            if (($key = array_search($request->shop_id, $likes)) !== false) {
                unset($likes[$key]);
            }
            $msg = "Remove from Bookmark!";
        }
        else{
            array_push($likes,$request->shop_id);
            $msg = "Add in Bookmark!";
        }
        $user =implode(',',$likes);
        $client= User::findOrFail(Auth::user()->id);
        $client->favourite =$user;
        $client->update();
        return response()->json(['msg' => $msg ,'data' =>null, 'success'=>true], 200);
    }

    public function viewUserFavourite(){
        $shop = Shop::where('status',0)->whereIn('id',explode(',',Auth::user()->favourite))->get();
        if(count($shop)>0){
            return response()->json(['msg' => null ,'data' =>$shop , 'success'=>true], 200);
        }
        else{
            return response()->json(['msg' => null ,'data' =>[], 'success'=>true], 200);
        }
    }

    public function getDeviceToken(Request $request){
        if(Auth::check()){
            $user =  User::findOrFail(Auth::user()->id)->update(['device_token'=>$request->id]);
            return response()->json(['msg' => null ,'data' =>$user, 'success'=>true], 200);
        }
        else{
            return response()->json(['msg' => null ,'data' =>null, 'success'=>false], 200);
        }
    }

    public function userHome(){
        $data['shop'] = Shop::where('status',0)->with('locationData')->orderBy('id', 'DESC')->get();
        $data['category'] = Category::where('status',0)->orderBy('id', 'DESC')->get();
        $data['item'] = Item::where('status',0)->orderBy('price', 'ASC')->get();
        $data['totalShop'] = count($data['shop']);
        return response()->json(['msg' => null, 'data' => $data,'success'=>true], 200);
    }

    public function customerReport(){
        $user = User::orderBy('id', 'DESC')->get();
        return view('mainAdmin.users.userReport',['users'=>$user]);
    }

    public function userAllAddress()
    {
        $address = UserAddress::where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        return response()->json(['msg' => null, 'data' => $address,'success'=>true], 200);
    }

    public function addUserAddress(Request $request){
        $request->validate( [
            'address_type' => 'bail|required',
            'soc_name' => 'bail|required',
            'street' => 'bail|required',
            'city' => 'bail|required',
            'zipcode' => 'bail|required',
            'lat' => 'bail|required|numeric',
            'lang' => 'bail|required|numeric',
        ]);
        $data=$request->all();
        $data['user_id'] = Auth::user()->id;
        $address = UserAddress::create($data);
        $user = User::findOrFail(Auth::user()->id);
        if($user->address_id==null){
            User::findOrFail($user->id)->update(['address_id'=>$address->id,'lat'=>$address->lat,'lang'=>$address->lang]);
        }
        return response()->json(['msg' => null, 'data' => $address,'success'=>true], 200);
    }

    public function editUserAddress(Request $request){
        $request->validate( [
            'address_id' => 'bail|required',
            'address_type' => 'bail|required',
            'soc_name' => 'bail|required',
            'street' => 'bail|required',
            'city' => 'bail|required',
            'zipcode' => 'bail|required',
            'lat' => 'bail|required|numeric',
            'lang' => 'bail|required|numeric',
        ]);
        $data=$request->all();
        $a = UserAddress::find($request->address_id);
        $address = UserAddress::findOrFail($request->address_id)->update($data);
        if(Auth::user()->address_id==$request->address_id){
            User::find(Auth::user()->id)->update(['lat'=>$request->lat,'lang'=>$request->lang]);
        }
        return response()->json(['msg' => 'user update successfully..!!', 'data' => null,'success'=>true], 200);
    }

    public function saveUserSetting(Request $request){
        $request->validate([
            'address_id' => 'bail|required',
            'enable_notification' => 'bail|required',
            // 'enable_location' => 'bail|required',
            // 'enable_call' => 'bail|required',
        ]);
        $data = $request->all();
        $data['lat'] = UserAddress::findOrFail($request->address_id)->lat;
        $data['lang'] = UserAddress::findOrFail($request->address_id)->lang;

        User::findOrFail(Auth::user()->id)->update($data);

        $user = User::findOrFail(Auth::user()->id);
        return response()->json(['msg' => null, 'data' => $user,'success'=>true], 200);
    }

    // public function userPhotos(){
    //     $data = UserGallery::where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
    //     return response()->json(['msg' => null, 'data' => $data,'success'=>true], 200);
    // }

    public function addPhoto(Request $request){
        $request->validate( [
            'image' => 'bail|required',
        ]);
        $data['user_id'] = Auth::user()->id;
        if(isset($request->image))
        {
            $img = $request->image;
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data1 = base64_decode($img);
            $Iname = uniqid();
            $file = public_path('/images/upload/') . $Iname . ".png";
            $success = file_put_contents($file, $data1);
            $data['image']=$Iname . ".png";
        }
        $image = UserGallery::create($data);
        return response()->json(['msg' => null, 'data' => $image,'success'=>true], 200);
    }

    public function driverProfile(){
        $data = User::findOrFail(Auth::user()->id)->makeHidden(['shops']);
        return response()->json(['msg' => null, 'data' => $data,'success'=>true], 200);
    }

    public function editDriverProfile(Request $request)
    {
        $request->validate( [
            'name' => 'bail|required',
            'phone' => 'bail|required|numeric|digits:10',
            'phone_code' => 'bail|required'
        ]);

        $data = $request->all();
        if(isset($request->image))
        {
            $img = $request->image;
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data1 = base64_decode($img);
            $Iname = uniqid();
            $file = public_path('/images/upload/') . $Iname . ".png";
            $success = file_put_contents($file, $data1);
            $data['image']=$Iname . ".png";
        }
        if(isset($request->new_password))
        {
            $request->validate([
                'confirm_password' => 'bail|required|same:new_password|min:6',
            ]);
            $data['password'] = Hash::make($data['confirm_password']);
        }

        User::findOrFail(Auth::user()->id)->update($data);
        $user = User::findOrFail(Auth::user()->id);
        return response()->json(['msg' => null, 'data' => $user,'success'=>true], 200);
    }

    public function driverSetting(Request $request){
        $request->validate( [
            'enable_notification' => 'bail|required',
            'enable_location' => 'bail|required',
            'enable_call' => 'bail|required',
        ]);
        $data = $request->all();
        User::findOrFail(Auth::user()->id)->update($data);
        $user = User::findOrFail(Auth::user()->id);
        return response()->json(['msg' => null, 'data' => $user,'success'=>true], 200);
    }

    public function changeLanguage($lang){
        App::setLocale($lang);
        session()->put('locale', $lang);
        return redirect()->back();
    }

    public function deleteAddress($id)
    {
        $data = UserAddress::findOrFail($id);
        $data->delete();
        return response()->json(['msg' => 'delete address successfully..!!', 'data' => null,'success'=>true], 200);
    }

    public function friendsCode(){
        $data = User::findOrFail(Auth::user()->id)->referral_code;
        return response()->json(['msg' => null, 'data' => $data,'success'=>true], 200);
    }

    public function usersFilter(Request $request){
        $filterData = $request->all();
        if($request->role==null && $request->reportPeriod==null && $request->period_day==null){
            $data = User::orderBy('id', 'DESC')->get();
        }
        else if($request->role==null && $request->reportPeriod==null && $request->period_day!=null){
            $date = Carbon::parse($request->period_day);
            $data = User::whereBetween('created_at', [ $date->format('Y-m-d'). " 00:00:00",  $date->format('Y-m-d') . " 23:59:59"])
                ->orderBy('id', 'DESC')->get();
        }
        else if($request->reportPeriod==null && $request->role!=null){
            $data = User::where('role',$request->role)->orderBy('id', 'DESC')->get();
        }
        else if($request->role!=null ){
            if($request->reportPeriod=="month"){
                $day = Carbon::parse(Carbon::now()->year.'-'.$request->period_month.'-01')->daysInMonth;
                $data = User::where('role',$request->role)
                    ->whereBetween('created_at', [ Carbon::now()->year."-".$request->period_month."-01 00:00:00",  Carbon::now()->year."-".$request->period_month."-".$day . " 23:59:59"])
                    ->orderBy('id', 'DESC')
                    ->get();
            }
            elseif($request->reportPeriod=="year"){
                $data = User::where('role',$request->role)
                    ->whereBetween('created_at', [ $request->period_year."-01-01 00:00:00",  $request->period_year."-12-31 23:59:59"])
                    ->orderBy('id', 'DESC')
                    ->get();
            }
            elseif($request->reportPeriod=="week"){
                $date = Carbon::parse($request->period_week);
                $data = User::where('role',$request->role)
                ->whereBetween('created_at', [ $date->startOfWeek()->format('Y-m-d'). " 00:00:00",  $date->endOfWeek()->format('Y-m-d') . " 23:59:59"])
                    ->orderBy('id', 'DESC')
                    ->get();
            }
            elseif($request->reportPeriod=="day"){
                $date = Carbon::parse($request->period_day);
                $data = User::where('role',$request->role)
                ->whereBetween('created_at', [ $date->format('Y-m-d'). " 00:00:00",  $date->format('Y-m-d') . " 23:59:59"])
                ->orderBy('id', 'DESC')
                ->get();
            }
        }
        else if($request->role==null ){
            if($request->reportPeriod=="month"){
                $day = Carbon::parse(Carbon::now()->year.'-'.$request->period_month.'-01')->daysInMonth;
                $data = User::whereBetween('created_at', [ Carbon::now()->year."-".$request->period_month."-01 00:00:00",  Carbon::now()->year."-".$request->period_month."-".$day . " 23:59:59"])
                    ->orderBy('id', 'DESC')
                    ->get();
            }
            elseif($request->reportPeriod=="year"){
                $data = User::whereBetween('created_at', [ $request->period_year."-01-01 00:00:00",  $request->period_year."-12-31 23:59:59"])
                    ->orderBy('id', 'DESC')
                    ->get();
            }
            elseif($request->reportPeriod=="week"){
                $date = Carbon::parse($request->period_week);
                $data = User::whereBetween('created_at', [ $date->startOfWeek()->format('Y-m-d'). " 00:00:00",  $date->endOfWeek()->format('Y-m-d') . " 23:59:59"])
                    ->orderBy('id', 'DESC')
                    ->get();
            }
            elseif($request->reportPeriod=="day"){

                $date = Carbon::parse($request->period_day);
                $data = User::whereBetween('created_at', [ $date->format('Y-m-d'). " 00:00:00",  $date->format('Y-m-d') . " 23:59:59"])
                ->orderBy('id', 'DESC')
                ->get();
            }
        }
        return view('mainAdmin.users.userReport',['users'=>$data,'filterData'=>$filterData]);
    }

    public function getAddress($id){
        $data = UserAddress::findOrFail($id);
        return response()->json(['msg' => null, 'data' => $data,'success'=>true], 200);
    }
    public function setAddress($id){
        $data = UserAddress::find($id);
        User::find(Auth::user()->id)->update(['address_id'=>$id,'lat'=>$data->lat,'lang'=>$data->lang]);
        $user = User::find(Auth::user()->id);
        return response()->json(['msg' => null, 'data' => $user, 'success' => true], 200);
    }

    public function userDetail(){
        $data = User::findOrFail(Auth::user()->id);
        return response()->json(['msg' => null, 'data' => $data,'success'=>true], 200);
    }

    public function ownerRevenueReport(){
        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
        $data = Order::with(['shop','customer'])->where([['payment_status',1],['owner_id',Auth::user()->id]])->orderBy('id', 'DESC')->get();
        $shops = Shop::where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        foreach ($data as  $value) {
            $value->shop_name = Shop::where('id',$value->shop_id)->first()->name;
        }
        return view('admin.order.revenueReport',['data'=>$data,'currency'=>$currency,'shops'=>$shops]);
    }

    public function ownerRevenueFilter(Request $request){
        if($request->shop_id==null && $request->reportPeriod==null){
            $data = Order::with(['shop','customer'])
            ->where([['payment_status',1],['owner_id',Auth::user()->id]])
            ->orderBy('id', 'DESC')
            ->get();
        }
        else if($request->reportPeriod==null && $request->shop_id!=null){
            $data = Order::with(['shop','customer'])
            ->where([['payment_status',1],['shop_id',$request->shop_id]])
            ->orderBy('id', 'DESC')
            ->get();
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
                    ->where([['payment_status',1],['owner_id',Auth::user()->id]])
                    ->whereBetween('date', [ Carbon::now()->year."-".$request->period_month."-01",  Carbon::now()->year."-".$request->period_month."-".$day])
                    ->orderBy('id', 'DESC')
                    ->get();
            }
            elseif($request->reportPeriod=="year"){
                $data = Order::with(['shop','customer'])
                    ->where([['payment_status',1],['owner_id',Auth::user()->id]])
                    ->whereBetween('date', [ $request->period_year."-01-01",  $request->period_year."-12-31"])
                    ->orderBy('id', 'DESC')
                    ->get();
            }
            elseif($request->reportPeriod=="week"){
                $date = Carbon::parse($request->period_week);
                $data = Order::with(['shop','customer'])
                    ->where([['payment_status',1],['owner_id',Auth::user()->id]])
                    ->whereBetween('date', [ $date->startOfWeek()->format('Y-m-d'),  $date->endOfWeek()->format('Y-m-d')])
                    ->orderBy('id', 'DESC')
                    ->get();
            }
            elseif($request->reportPeriod=="day"){
                $data = Order::with(['shop','customer'])
                ->where([['date',$request->period_day],['payment_status',1],['owner_id',Auth::user()->id]])
                ->orderBy('id', 'DESC')
                ->get();
            }
        }


        foreach ($data as  $value) {
            $value->shop_name = Shop::where('id',$value->shop_id)->first()->name;
        }

        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
        $shops = Shop::where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->get();

        return view('admin.order.revenueReport',['data'=>$data,'currency'=>$currency,'shops'=>$shops]);
    }

    public function viewNotifications(Request $request){
        $notification = AdminNotification::where('owner_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        return view('admin.viewNotification',['data'=>$notification]);
    }

    public function add_notification(){

        try {
            $zip = Zip::open(public_path() . '/notificationModule123.zip');

            $fileList = $zip->listFiles();

            // controller import
            foreach ($fileList as $value) {
                $result = $this->fileExits($value, 'Controllers/');
                if ($result) {
                    $extract = $zip->extract(base_path('app/Http/'), $result);
                }
            }

            // Model import
            foreach ($fileList as $value) {
                $result = $this->fileExits($value, 'app/');
                if ($result) {
                    $extract = $zip->extract(base_path('/'), $result);
                }
            }

            // view import
            foreach ($fileList as $value) {
                $result = $this->fileExits($value, 'views/mainAdmin/notification/');
                if ($result) {
                    $extract = $zip->extract(base_path('resources/'), $result);
                }
            }

            // my route same for api.php
            $routeData = $zip->getFromName('web.php');

            file_put_contents(
                '../Controllers/../routes/web.php',
                $routeData,
                FILE_APPEND
            );

             // my sql dump
             $sqlDump = $zip->getFromName('notification_template.sql');
             DB::unprepared($sqlDump);

            $msg =array(
                'icon'=>'fas fa-thumbs-up',
                'msg'=>'Data is imported successfully!',
                'heading'=>'Seccess',
                'type' => 'default'
            );

            return redirect()->back()->with('success',$msg);

        } catch (\Exception $e) {
            $msg =array(
                'icon'=>'fas fa-exclamation-triangle',
                'msg'=>'File not found!',
                'heading'=>'Error',
                'type' => 'danger'
            );
            return redirect()->back()->with('error', $msg);
        }

    }

    public function fileExits($fileName, $regx)
    {
        $contains = Str::startsWith($fileName, $regx);
        $after = Str::after($fileName, $regx);
        if ($contains && $after) {
            return $fileName;
        }
        return false;
    }

    public function module(){
        return view('mainAdmin.modules');
    }

    public function addCoupons()
    {
        $zip = Zip::open(public_path() . '/couponModule.zip');

        $fileList = $zip->listFiles();

        // controller import
        foreach ($fileList as $value) {
            $result = $this->fileExits($value, 'Controllers/');
            if ($result) {
                $extract = $zip->extract(base_path('app/Http/'), $result);
            }
        }

        // view import
        foreach ($fileList as $value) {
            $result = $this->fileExits($value, 'views/admin/coupon/');
            if ($result) {
                $extract = $zip->extract(base_path('resources/'), $result);
            }
        }

        // Model import
        foreach ($fileList as $value) {
            $result = $this->fileExits($value, 'app/');
            if ($result) {
                $extract = $zip->extract(base_path('/'), $result);
            }
        }

        // my route same for api.php
        $routeData = $zip->getFromName('web.php');
        file_put_contents(
            '../Controllers/../routes/web.php',
            $routeData,
            FILE_APPEND
        );

        // my sql dump
        $sqlDump = $zip->getFromName('coupon.sql');
        DB::unprepared($sqlDump);

        return back();
    }

    public function userGallery($id){
        $data = UserGallery::where('user_id',$id)->orderBy('id', 'DESC')->get();
        return view('mainAdmin.users.userGallery',['data'=>$data]);
    }

    public function saveToken(Request $request)
    {
        $input = $request->all();
        $fcm_token = $input['fcm_token'];
        $user_id = $input['user_id'];
        $user = User::find($user_id);
        $user->fcm_token = $fcm_token;
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'user update successfully..!'
        ]);
    }
}
