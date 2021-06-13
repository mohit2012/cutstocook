<?php

namespace App\Http\Controllers;

use App\Package;
use Auth;
use DB;
use App\Shop;
use App\Currency;
use App\Setting;
use App\Item;
use App\OrderChild;
use Illuminate\Http\Request;

class PackageController extends Controller
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
        $data = Package::where('owner_id',Auth::user()->id)->orderBy('id', 'DESC')->paginate(7);
       
        return view('admin.package.viewPackage',['packages'=>$data,'currency'=>$currency]);
       
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $data = Shop::where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        $shop = array();
        foreach ($data as $value) {
            array_push($shop,$value->id); 
        }
        $item = Item::whereIn('shop_id',$shop)->get();
      
        return view('admin.package.addPackage',['shops'=>$data,'items'=>$item]);
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
            'name' => 'bail|required|unique:package',
            'items' => 'bail|required',
            'shop_id' => 'bail|required',
            'package_price' => 'bail|required',
            'status' => 'bail|required',
          'image' => 'bail|required',
        ]);
        $data = $request->all();
        $item = Item::whereIn('id',$data['items'])->get();
        $price = 0;
        foreach ($item as $value) {
            $price = $price +$value->price; 
        }
        $data['total_price'] = $price;
        $data['owner_id'] = Auth::user()->id;
        $data['items'] = implode(',',$data['items']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');           
            $name = uniqid() . '.' . $image->getClientOriginalExtension();           
            $destinationPath = public_path('/images/upload');            
            $image->move($destinationPath, $name);                
            $data['image'] = $name;           
        }

        $data = Package::create($data);
        return redirect('owner/Package');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $shops = Shop::where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        $shop = array();
        foreach ($shops as $value) {
            array_push($shop,$value->id); 
        }
        $item = Item::whereIn('shop_id',$shop)->get();
        $data = Package::findOrFail($id);
        return view('admin.package.editPackage',['data'=>$data,'shops'=>$shops,'items'=>$item]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        $request->validate([
            'name' => 'bail|required|unique:package,name,' . $id . ',id',
            'items' => 'bail|required',
            'shop_id' => 'bail|required',
            'package_price' => 'bail|required',
            'status' => 'bail|required',        
        ]);
        $data = $request->all();
        $item = Item::whereIn('id',$data['items'])->get();
        $price = 0;
        foreach ($item as $value) {
            $price = $price +$value->price; 
        }
        $data['total_price'] = $price;        
        $data['items'] = implode(',',$data['items']);
        if ($request->hasFile('image')) {
            $image = $request->file('image');           
            $name = uniqid() . '.' . $image->getClientOriginalExtension();           
            $destinationPath = public_path('/images/upload');            
            $image->move($destinationPath, $name);                
            $data['image'] = $name;           
        }
        $data = Package::findOrFail($id)->update($data);
        return redirect('owner/Package');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {           
            $delete = Package::find($id);
            $child = OrderChild::where('package_id',$id)->get();
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
}
