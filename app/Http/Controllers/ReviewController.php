<?php

namespace App\Http\Controllers;

use App\Review;
use Auth;
use App\Order;
use App\UserAddress;
use App\UserGallery;
use App\GroceryReview;
use App\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        //
    }

    public function viewUserReview()
    {
        $master = array();
        $master['review'] = Review::where('customer_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        $master['userDetail'] = User::where('id',Auth::user()->id)->first();
        $master['userAddress'] = UserAddress::where('id',$master['userDetail']->address_id)->first();
        $master['photos'] = UserGallery::where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        return response()->json(['msg' => null, 'data' => $master,'success'=>true], 200);
    }

    public function addDriverReview(Request $request){
        $request->validate([
            'order_id' => 'bail|required',
            'customer_id' => 'bail|required',
            'deliveryBoy_id' => 'bail|required',
            'message' => 'bail|required',
            'rate' => 'bail|required',
        ]);
        $data = $request->all();
        $review = Review::create($data);
        Order::findOrFail($request->order_id)->update(['driverReview_status'=>1]);
        return response()->json(['msg' => null, 'data' => $review,'success'=>true], 200);

    }
    public function addShopReview(Request $request){
        $request->validate([
            'order_id' => 'bail|required',
            'shop_id' => 'bail|required',
            'message' => 'bail|required',
            'rate' => 'bail|required',
        ]);
        $data = $request->all();
        $data['customer_id'] = auth()->user()->id;
        $review = Review::create($data);
        Order::findOrFail($request->order_id)->update(['shopReview_status'=>1]);
        return response()->json(['msg' => null, 'data' => $review,'success'=>true], 200);

    }

    public function addItemReview(Request $request){
        $request->validate([
            'order_id' => 'bail|required',
            'customer_id' => 'bail|required',
            'itemData' => 'bail|required',
        ]);
        $data = $request->all();
        $master = array();

        foreach ($data['itemData'] as $value) {
            $review['order_id'] = $request->order_id;
            $review['customer_id'] = $request->customer_id;
            $review['item_id'] = $value['item_id'];
            $review['package_id'] = $value['package_id'];
            $review['rate'] = $value['rate'];
            $review['message'] = $value['message'];

            $rate = Review::create($review);
            array_push($master,$rate);
        }
        Order::findOrFail($request->order_id)->update(['review_status'=>1]);
        return response()->json(['msg' => null, 'data' => $master,'success'=>true], 200);
    }

    public function itemReview($id){
        $data = Review::where('item_id',$id)->orderBy('id', 'DESC')->get();
        return response()->json(['msg' => null, 'data' => $data,'success'=>true], 200);
    }

    public function driverReview(){
        $master = array();
        $master['food'] = Review::where('deliveryBoy_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        $master['grocery'] = GroceryReview::where('deliveryBoy_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        return response()->json(['msg' => null, 'data' => $master,'success'=>true], 200);
    }
}
