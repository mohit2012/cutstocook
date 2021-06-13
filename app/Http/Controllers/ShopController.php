<?php

namespace App\Http\Controllers;

use App\Shop;
use App\Location;
use App\Setting;
use App\Currency;
use Carbon\Carbon;
use App\Coupon;
use App\Category;
use App\Order;
use App\Notification;
use App\Package;
use App\Gallery;
use App\AdminNotification;
use App\User;
use App\Review;
use App\OrderChild;
use App\Item;
use Auth;
use Illuminate\Http\Request;
use Excel;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if(Auth::check()){
            $data = Shop::with('locationData')->where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->paginate(10);
            return view('admin.shop.viewShop',['shops'=>$data]);
        }
        elseif(Auth::guard('mainAdmin')->check())
        {
            $data = Shop::with(['locationData','user'])->orderBy('id', 'DESC')->paginate(10);
            return view('mainAdmin.shop.viewShop',['shops'=>$data]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $location = Location::get();
        if(Auth::check()){
            return view('admin.shop.addShop',['location'=>$location]);
        }
        elseif(Auth::guard('mainAdmin')->check()){
            $user = User::where('role',1)->get();
            return view('mainAdmin.shop.addShop',['location'=>$location,'users'=>$user]);
        }

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
            'name' => 'bail|required|unique:shop',
            'address' => 'bail|required',
            'description' => 'bail|required',
            'latitude' => 'bail|required|numeric',
            'longitude' => 'bail|required|numeric',
            'pincode' => 'bail|required|numeric',
            'delivery_time' => 'bail|required|numeric',
            'licence_code' => 'bail|required',
            'rastaurant_charge' => 'bail|required|numeric',
            'delivery_charge' => 'bail|required|numeric',
            'radius'=> 'bail|required|numeric',
            'status' => 'bail|required',
            'image' => 'bail|required',
            'location' => 'bail|required',
            'cancle_charge' => 'bail|required',
        ]);

        $data = $request->all();
        if(isset($request->veg)){ $data['veg'] = 1; }
        else{ $data['veg'] = 0; }

        if(isset($request->featured)){ $data['featured'] = 1; }
        else{ $data['featured'] = 0; }

        if(isset($request->exclusive)){ $data['exclusive'] = 1; }
        else{ $data['exclusive'] = 0; }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }
        if(Auth::check()){
            $data['user_id'] = Auth::user()->id;
        }
        elseif(Auth::guard('mainAdmin')->check()){
            $data['user_id'] = $request->user_id;
        }
        $data['open_time'] = Carbon::parse($data['open_time'])->format('g:i A');
        $data['close_time'] = Carbon::parse($data['close_time'])->format('g:i A');
        $shop = Shop::create($data);

        if(Auth::check()){
            return redirect('owner/Shop');
        }
        elseif(Auth::guard('mainAdmin')->check()){
            return redirect('mainAdmin/AdminShop');
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $shop = Shop::where('name',$id)->first();
        $shop->items = Item::where('shop_id',$shop->id)->get();

        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;

        if(Auth::check()){
            return view('admin.shop.shopDetail',['data'=>$shop,'currency'=>$currency]);
        }
        elseif(Auth::guard('mainAdmin')->check()){
            return view('mainAdmin.shop.shopDetail',['data'=>$shop,'currency'=>$currency]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $data = Shop::find($id);
        $data['open_time'] = Carbon::parse($data->open_time)->format('H:i');
        $data['close_time'] = Carbon::parse($data->close_time)->format('H:i');

        $location = Location::get();
        if(Auth::check()){
            return view('admin.shop.editShop',['data'=>$data,'location'=>$location]);
        }
        elseif(Auth::guard('mainAdmin')->check()){
            $user = User::where('role',1)->get();
            return view('mainAdmin.shop.editShop',['data'=>$data,'location'=>$location,'users'=>$user]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        $request->validate([
            'name' => 'bail|required|unique:shop,name,' . $id . ',id',
            'address' => 'bail|required',
            'description' => 'bail|required',
            'latitude' => 'bail|required|numeric',
            'longitude' => 'bail|required|numeric',
            'pincode' => 'bail|required|numeric',
            'delivery_time' => 'bail|required|numeric',
            'licence_code' => 'bail|required',
            'rastaurant_charge' => 'bail|required|numeric',
            'delivery_charge' => 'bail|required|numeric',
            'avarage_plate_price' => 'bail|required|numeric',
            'status' => 'bail|required',
            'radius'=> 'bail|required|numeric',
            'cancle_charge' => 'bail|required',
        ]);

        $data = $request->all();
        if(isset($request->veg)){ $data['veg'] = 1; }
        else{ $data['veg'] = 0; }

        if(isset($request->featured)){ $data['featured'] = 1; }
        else{ $data['featured'] = 0; }

        if(isset($request->exclusive)){ $data['exclusive'] = 1; }
        else{ $data['exclusive'] = 0; }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }
        if(Auth::check()){
            $data['user_id'] = Auth::user()->id;
        }
        elseif(Auth::guard('mainAdmin')->check()){
            $data['user_id'] = $request->user_id;
        }
        $data['open_time'] = Carbon::parse($data['open_time'])->format('g:i A');
        $data['close_time'] = Carbon::parse($data['close_time'])->format('g:i A');
        $shop = Shop::find($id)->update($data);

        if(Auth::check()){
            return redirect('owner/Shop');
        }
        elseif(Auth::guard('mainAdmin')->check()){
            return redirect('mainAdmin/AdminShop');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $Item = Item::where('shop_id',$id)->get();
            if(count($Item)>0){
                foreach ($Item as $value) {
                    $value->delete();
                }
            }

            $Package = Package::where('shop_id',$id)->get();
            if(count($Package)>0){
                foreach ($Package as $value) {
                    $value->delete();
                }
            }

            $Coupon = Coupon::where([['shop_id',$id],'use_for'=>'Food'])->get();
            if(count($Coupon)>0){
                foreach ($Coupon as $value) {
                    $value->delete();
                }
            }

            $Gallery = Gallery::where('shop_id',$id)->get();
            if(count($Gallery)>0){
                foreach ($Gallery as $value) {
                    $value->delete();
                }
            }

            $Order = Order::where('shop_id',$id)->get();
            if(count($Order)>0){
                foreach ($Order as $value) {
                    $Notification = Notification::where([['order_id',$value->id],['notification_type','Food']])->get();
                    if(count($Item)>0){
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
            $delete = Shop::find($id);
            $delete->delete();
            return 'true';
        } catch (\Exception $e) {
            return response('Data is Connected with other Data', 400);
        }
    }

    public function viewShop(){
        $data = Shop::where('status',0)->with('locationData')->orderBy('id', 'DESC')->get();
        return response()->json(['msg' => null, 'data' => $data,'success'=>true], 200);
    }

    public function categoryShop($id)
    {
        $shop = array();
        $service = Item::where([['category_id',$id],['status',0]])->get();
        foreach ($service as $value) {
            array_push($shop,$value->shop_id);
        }
        $data = Shop::where('status',0)->whereIn('id',$shop)->orderBy('id', 'DESC')->get();
        return response()->json(['msg' => null, 'data' => $data,'success'=>true], 200);
    }

    public function shopDetail($id)
    {
        $data = Shop::with('locationData')->find($id);
        $data['bestSeller'] = Item::where([['shop_id',$data->id],['status',0]])->orderBy('id', 'DESC')->get();
        $data['coupon'] = Coupon::where([['shop_id',$data->id],['status',0]])->orderBy('id', 'DESC')->get();
        $data['review'] = Review::where('shop_id',$data->id)->orderBy('id', 'DESC')->get();
        $data['gallery'] = Gallery::where('shop_id',$data->id)->orderBy('id', 'DESC')->get();
        $data['combo'] = Package::where([['shop_id',$data->id],['status',0]])->orderBy('id', 'DESC')->get();
        foreach ($data['combo'] as $value) {
            $value->price = $value->package_price;
        }

        if(auth('api')->user()!=null){
            $favorite = User::find(auth('api')->user()->id)->favourite;
            $likes=array_filter(explode(',',$favorite));
            if(count(array_keys($likes,$id))>0){
                $data->isSeved = true;
            }
            else{
                $data->isSeved = false;
            }
        }
        else{
            $data->isSeved = false;
        }
        $master = array();
        $item = Item::where('shop_id',$id)->get();
        $category = array();
        foreach ($item as $value) { array_push($category,$value->category_id); }
        $categories = Category::whereIn('id',$category)->get();
        foreach ($categories as $key) {
            $a = Item::where([['shop_id',$id],['category_id',$key->id]])->get()->count();
            $temp['name'] = $key->name;
            $temp['items'] = $a;
            array_push($master,$temp);
        }
        $combo = Package::where('shop_id',$id)->get()->count();
        if($combo>0){
            $temp['name'] = 'Combo';
            $temp['items'] = $combo;
            array_push($master,$temp);
        }
        $data['menu'] = $master;
        return response()->json(['msg' => null, 'data' => $data,'success'=>true], 200);
    }

    public function shopReviews($id){
        $data =  Review::where('shop_id',$id)->get();
        foreach ($data as $value) {
            $value->order_no = Order::find($value->order_id)->order_no;
        }
        return view('admin.shop.shopReview',['data'=>$data]);
    }

    public function getShopData($id){

        $shop = Shop::find($id);
        return response()->json(['data' => $shop,'success'=>true], 200);

    }

    public function adminShopReview($id)
    {
        $data =  Review::where('shop_id',$id)->get();
        foreach ($data as $value) {
            $value->order_no = Order::find($value->order_id)->order_no;
        }
        return view('mainAdmin.shop.shopReview',['data'=>$data]);
    }
}
