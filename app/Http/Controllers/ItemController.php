<?php

namespace App\Http\Controllers;

use App\Item;
use App\Category;
use App\Shop;
use App\OrderChild;
use App\Order;
use App\Setting;
use App\Currency;
use Auth;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
        if(Auth::check()){
             $item = Item::with(['category','shop'])->orderBy('id', 'DESC')->paginate(10);

            return view('admin.item.viewItem',['items'=>$item,'currency'=>$currency]);
        }
        elseif(Auth::guard('mainAdmin')->check()){
            $data = Item::with(['category','shop'])->orderBy('id', 'DESC')->paginate(10);
            return view('mainAdmin.item.viewItem',['items'=>$data,'currency'=>$currency]);
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
        $category = Category::get();

        if(Auth::check()){
            $shop = Shop::where('user_id',Auth::user()->id)->get();
            return view('admin.item.addItem',['category'=>$category,'shop'=>$shop]);
        }
        elseif(Auth::guard('mainAdmin')->check()){
            $shop = Shop::get();
            return view('mainAdmin.item.addItem',['category'=>$category,'shop'=>$shop]);
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
            'name' => 'bail|required|unique:item',
            'price' => 'bail|required',
            'description' => 'bail|required',
            'category_id' => 'bail|required',
            'shop_id' => 'bail|required',
            'status' => 'bail|required',
            'image' => 'bail|required',
            'isVeg' => 'bail|required',
        ]);
        $data = $request->all();

        if(isset($request->isNew)){ $data['isNew'] = 1; }
        else{ $data['isNew'] = 0; }

        if(isset($request->isPopular)){ $data['isPopular'] = 1; }
        else{ $data['isPopular'] = 0; }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }

        $data = Item::create($data);
        if(Auth::check()){
            return redirect('owner/Item');
        }
        elseif(Auth::guard('mainAdmin')->check()){
            return redirect('mainAdmin/AdminItem');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $data = Item::with(['category','shop'])->findOrFail($id);
        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
        return view('admin.item.itemDetail',['data'=>$data,'currency'=>$currency]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $category = Category::get();
        $data = Item::findOrFail($id);
        if(Auth::check()){
            $shop = Shop::where('user_id',Auth::user()->id)->get();
            return view('admin.item.editItem',['category'=>$category,'shop'=>$shop,'data'=>$data]);
        }
        elseif(Auth::guard('mainAdmin')->check()){
            $shop = Shop::get();
            return view('mainAdmin.item.editItem',['category'=>$category,'shop'=>$shop,'data'=>$data]);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'name' => 'bail|required|unique:item,name,' . $id . ',id',
            'price' => 'bail|required',
            'description' => 'bail|required',
            'category_id' => 'bail|required',
            'shop_id' => 'bail|required',
            'status' => 'bail|required',
            'isVeg' => 'bail|required',
        ]);
        $data = $request->all();
        if(isset($request->isNew)){ $data['isNew'] = 1; }
        else{ $data['isNew'] = 0; }

        if(isset($request->isPopular)){ $data['isPopular'] = 1; }
        else{ $data['isPopular'] = 0; }
        // if(isset($request->isVeg)){ $data['isVeg'] = 1; }
        // else{ $data['isVeg'] = 0; }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }

        $data = Item::findOrFail($id)->update($data);
        if(Auth::check()){
            return redirect('owner/Item');
        }
        elseif(Auth::guard('mainAdmin')->check()){
            return redirect('mainAdmin/AdminItem');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {

            $delete = Item::find($id);
            $child = OrderChild::where('item',$id)->get();
            if(count($child)==0){
                $delete->delete();
                return 'true';
            }
            else{
                return response('Data is Connected with other Data', 400);
            }
        } catch (\Exception $e) {
            return response('Data is Connected with other Data', 400);
        }
    }

    public function viewShopItem($id)
    {
        $data = Item::where([['shop_id',$id],['status',0]])->orderBy('id', 'DESC')->get();
        return response()->json(['msg' => null, 'data' => $data,'success'=>true], 200);
    }
}
