<?php

namespace App\Http\Controllers;

use App\Coupon;
use Auth;
use App\GroceryShop;
use App\Shop;
use App\Setting;
use App\Currency;
use Illuminate\Http\Request;

class GroceryCouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $s = array();
        $shop =  GroceryShop::where('user_id',Auth::user()->id)->get();
        foreach ($shop as $value) {
            array_push($s, $value->id);
        }
        $data = Coupon::where('use_for','Grocery')->whereIn('shop_id',$s)->orderBy('id', 'DESC')->get();

        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;

        return view('admin.coupon.viewGroceryCoupon',['coupons'=>$data,'currency'=>$currency]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $shop = GroceryShop::where('user_id',Auth::user()->id)->get();
        return view('admin.coupon.addGroceryCoupon',['shops'=>$shop]);
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
        $request->validate( [
            'name' => 'bail|required',
            'shop_id' => 'bail|required',
            'type' => 'bail|required',
            'discount' => 'bail|required',
            'max_use' => 'bail|required',
            'start_date' => 'bail|required',
            'status' => 'bail|required',
        ]);
        $data = $request->all();
        $date = explode(" to ",$request->start_date);
        $data['start_date'] = $date[0];
        $data['end_date'] = $date[1];
        $data['use_for'] = 'Grocery';
        $data['code'] = chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).'-'.rand(999,10000);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }
        Coupon::create($data);
        return redirect('owner/GroceryCoupon');
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
        $data = Coupon::where('code',$id)->first();
        $shop = Shop::where('user_id',Auth::user()->id)->get();
        $groceryShop = GroceryShop::where('user_id',Auth::user()->id)->get();
        return view('admin.coupon.editCoupon',['data'=>$data,'shops'=>$shop,'groceryShop'=>$groceryShop]);
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
        $request->validate( [
            'name' => 'bail|required',
            'shop_id' => 'bail|required',
            'type' => 'bail|required',
            'discount' => 'bail|required',
            'max_use' => 'bail|required',
            'start_date' => 'bail|required',
            'status' => 'bail|required',
        ]);
        $data = $request->all();
        if($request->start_date){
            $date = explode(" to ",$request->start_date);
            $data['start_date'] = $date[0];
            $data['end_date'] = $date[1];
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }
        Coupon::find($id)->update($data);
        return redirect('owner/GroceryCoupon');
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
            $delete = Coupon::find($id);
            $delete->delete();
            return 'true';
        } catch (\Exception $e) {
            return response('Data is Connected with other Data', 400);
        }
    }

    public function viewGroceryCoupon(){
        $data = Coupon::where([['status',0],['use_for','Grocery']])->orderBy('id', 'DESC')->get();
        return response()->json(['msg' => null, 'data' => $data,'success'=>true], 200);
    }

    public function viewGroceryShopCoupon($id){
        $data = Coupon::where([['shop_id',$id],['status',0],['use_for','Grocery']])->orderBy('id', 'DESC')->get();
        return response()->json(['msg' => null, 'data' => $data,'success'=>true], 200);
    }
}
