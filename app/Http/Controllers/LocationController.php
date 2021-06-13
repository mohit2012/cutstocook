<?php

namespace App\Http\Controllers;

use App\Location;
use App\Shop;
use App\Item;
use App\Package;
use App\Coupon;
use App\Gallery;
use App\GrocerySubCategory;
use App\GroceryOrder;
use App\GroceryOrderChild;
use App\GroceryReview;
use App\GroceryItem;
use App\GroceryShop;
use App\Order;
use App\Notification;
use App\Review;
use App\OrderChild;
use App\AdminNotification;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $location = Location::orderBy('id', 'DESC')->paginate(10);
        return view('mainAdmin.location.viewLocation',['locations'=>$location]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('mainAdmin.location.addLocation');
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
            'name' => 'bail|required|unique:location',
            'status' => 'bail|required',
            'latitude' => 'bail|required|numeric',
            'longitude' => 'bail|required|numeric',
            'radius' => 'bail|required|numeric',
            'description' => 'bail|required',
        ]);
        $data = $request->all();

        if(isset($request->popular)){ $data['popular'] = 1; }
        else{ $data['popular'] = 0; }
        Location::create($data);
        return redirect('mainAdmin/Location');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $data = Location::findOrFail($id);
        return view('mainAdmin.location.editLocation',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'name' => 'bail|required|unique:location,name,' . $id . ',id',
            'status' => 'bail|required',
            'latitude' => 'bail|required|numeric',
            'longitude' => 'bail|required|numeric',
            'radius' => 'bail|required|numeric',
            'description' => 'bail|required',
        ]);
        $data = $request->all();

        if(isset($request->popular)){ $data['popular'] = 1; }
        else{ $data['popular'] = 0; }
           
        Location::findOrFail($id)->update($data);
        return redirect('mainAdmin/Location');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 
        try {
            $Shop = Shop::where('location',$id)->get();
            if(count($Shop)>0){
                foreach ($Shop as $value) {
                    $Item = Item::where('shop_id',$value->id)->get();
                    if(count($Item)>0){
                        foreach ($Item as $i) {
                            $i->delete();
                        } 
                    }                
                    $Package = Package::where('shop_id',$value->id)->get();
                    if(count($Package)>0){
                        foreach ($Package as $p) {
                            $p->delete();
                        } 
                    }
                    $Coupon = Coupon::where([['shop_id',$value->id],['use_for','Food']])->get();
                    if(count($Coupon)>0){
                        foreach ($Coupon as $c) {
                            $c->delete();
                        } 
                    }
                    $Gallery = Gallery::where('shop_id',$value->id)->get();
                    if(count($Gallery)>0){
                        foreach ($Gallery as $g) {
                            $g->delete();
                        } 
                    }

                    $Order = Order::where('shop_id',$value->id)->get();
                    if(count($Order)>0){
                        foreach ($Order as $item) {                    
                            $Notification = Notification::where([['order_id',$item->id],['notification_type','Food']])->get();
                            if(count($Notification)>0){
                                foreach ($Notification as $n) {
                                    $n->delete();
                                } 
                            }
                            $Review = Review::where('order_id',$item->id)->get();
                            if(count($Review)>0){
                                foreach ($Review as $r) {
                                    $r->delete();
                                } 
                            }
                            $OrderChild = OrderChild::where('order_id',$item->id)->get();
                            if(count($OrderChild)>0){
                                foreach ($OrderChild as $oc) {
                                    $oc->delete();
                                } 
                            }
                            $AdminNotification = AdminNotification::where('order_id',$item->id)->get();
                            if(count($AdminNotification)>0){
                                foreach ($AdminNotification as $an) {
                                    $an->delete();
                                } 
                            }                    
                            $item->delete();
                        } 
                    } 
                    $value->delete();
                } 
            }

            $Groceryshop = GroceryShop::where('location',$id)->get();
            if(count($Groceryshop)>0){
                foreach ($Groceryshop as $value) {
                    $GroceryItem = GroceryItem::where('shop_id',$value->id)->get();
                    if(count($GroceryItem)>0){
                        foreach ($GroceryItem as $i) {
                            $i->delete();
                        } 
                    }  
                    $GrocerySubCategory = GrocerySubCategory::where('shop_id',$value->id)->get();                   
                    if(count($GrocerySubCategory)>0){
                        foreach ($GrocerySubCategory as $g) {                           
                            $g->delete();
                        } 
                    }

                   
                  
                    $Coupon = Coupon::where([['shop_id',$value->id],['use_for','Grocery']])->get();
                    if(count($Couponv)>0){
                        foreach ($Coupon as $c) {
                            $c->delete();
                        } 
                    }                                       

                    $Order = GroceryOrder::where('shop_id',$value->id)->get();
                    if(count($Order)>0){
                        foreach ($Order as $item) {                    
                            $Notification = Notification::where([['order_id',$item->id],['notification_type','Grocery']])->get();
                            if(count($Notification)>0){
                                foreach ($Notification as $n) {
                                    $n->delete();
                                } 
                            }
                            $Review = GroceryReview::where('order_id',$item->id)->get();
                            if(count($Review)>0){
                                foreach ($Review as $r) {
                                    $r->delete();
                                } 
                            }
                            $OrderChild = GroceryOrderChild::where('order_id',$item->id)->get();
                            if(count($OrderChild)>0){
                                foreach ($OrderChild as $oc) {
                                    $oc->delete();
                                } 
                            }                                              
                            $item->delete();
                        } 
                    } 
                    $value->delete();
                } 
            }

            $delete = Location::findOrFail($id);
            $delete->delete();
            return 'true';
        } catch (\Exception $e) {
            return response('Data is Connected with other Data', 400);
        }
    }
}
