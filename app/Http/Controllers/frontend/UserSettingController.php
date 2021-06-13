<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\controller;
use App\Currency;
use App\GroceryOrder;
use App\GroceryReview;
use App\Item;
use App\Order;
use App\OrderChild;
use App\Review;
use App\Setting;
use App\User;
use App\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSettingController extends Controller
{
    public function settings()
    {
        $user = auth()->user();
        $data = UserAddress::where('user_id', auth()->user()->id)->get();
        return view('frontend.settings',compact('user','data'));
    }
    public function update_profile(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|digits:10',
        ]);
        $id = User::find(auth()->user()->id);
        $data = $request->all();
        if ($file = $request->hasfile('image'))
        {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $path = public_path() . '/images/upload/';
            $file->move($path, $fileName);
            $data['image'] = $fileName;
        }
        $data = $id->update($data);
        return redirect('settings');
    }

    public function notification(Request $request)
    {
        $check = $request->check;
        $id = Auth::user()->id;
        $user = User::find($id);
        if($check == "checked")
        {
            $user->enable_notification = 1;
        }
        else
        {
            $user->enable_notification = 0;
        }
        $user->save();
        return response(['success'=>true]);
    }

    public function getUserToken(Request $request)
    {
        if(Auth::check())
        {
            $id = Auth::user()->id;
            $user = User::find($id);
            $user->device_token = $request->id;
            $user->save();
            return ['success'=> true, 'data'=> 'device token updated'];
        }
        return ['success'=> false, 'data'=> 'unauthorized'];
    }

    public function user_details()
    {
        $data = User::find(auth()->user()->id);
        $order_on_way = Order::with(['location','shop','orderItem'])->where('customer_id',auth()->user()->id)->orderBy('created_at','DESC')->get();
        $grocery_order = GroceryOrder::where('customer_id',auth()->user()->id)->orderBy('created_at','DESC')->get();
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        $reviews = Review::where('customer_id',auth()->user()->id)->get();
        $grocery_reviews = GroceryReview::where('customer_id',auth()->user()->id)->get();
        return view('frontend.user_details',compact('data','order_on_way','currency','grocery_order','reviews','grocery_reviews'));
    }

    public function edit_address(Request $request)
    {
        $id = UserAddress::find($request->id);
        $homeChecked = $id->address_type == 'home' ? 'checked' : '';
        $officeChecked = $id->address_type == 'office' ? 'checked' : '';
        $auth_user = auth()->user();
        $default_address_id = $auth_user->address_id;
        $default_address = $default_address_id == $request->id ? 'checked' : '';
        $address = '';
        $address .= '';
        $address .= '<div class="t1">Edit Address</div><br>';
        $address .= '<input type="radio" value="home" name="address_type" id="address_type" '. $homeChecked .'>Home';
        $address .= '<input type="radio" value="office" name="address_type" id="address_type" '. $officeChecked .'>Office';
        $address .= '<input type="hidden" id="id" value="'. $id->id .'">';
        $address .= '<input type="text" name="text" id="soc_name" value="'. $id->soc_name .'" placeholder="sociaty name.." class="form-control mt-2"> ';
        $address .= '<input type="text" name="text" id="street" value="'. $id->street .'" placeholder="street.." class="form-control mt-2">';
        $address .= '<input type="text" name="text" id="city" value="'. $id->city .'" placeholder="city" class="form-control mt-2">';
        $address .= '<input type="text" name="text" id="zipcode" value="'. $id->zipcode .'" placeholder="zip code" class="form-control mt-2">';
        $address .= '<input type="checkbox" name="default_address" value="1" '. $default_address .'>Set As Default';
        $address .= '<input type="hidden" name="lat" id="lat" value="22.3039">';
        $address .= '<input type="hidden" name="lang" id="lang" value="70.8022">';
        $address .= '<div id="address_map" class="mb-5 form-map"></div>';
        $address .= '<br><input type="button" value="edit" class="btn bg-blue text-white" onclick="update_address_setting()">';
        return response(['data'=>$address , 'success'=>true]);
    }

    public function update_address(Request $request)
    {
        $data = $request->all();
        if($request->default_address == 1)
        {
            $id = UserAddress::find($request->id);
            $id->soc_name = $request->soc_name;
            $id->address_type = $request->address_type;
            $id->street = $request->street;
            $id->city = $request->city;
            $id->zipcode = $request->zipcode;
            $id->lat = $request->lat;
            $id->lang = $request->lang;
            $id->save();
            $address = UserAddress::find($request->id);
            $user = User::find(auth()->user()->id);
            $user->address_id = $address->id;
            $user->lat = $address->lat;
            $user->lang = $address->lang;
            $user->save();
        }
        $id = UserAddress::find($request->id);
        $id->soc_name = $request->soc_name;
        $id->address_type = $request->address_type;
        $id->street = $request->street;
        $id->city = $request->city;
        $id->lat = $request->lat;
        $id->lang = $request->lang;
        $id->zipcode = $request->zipcode;
        $id->save();
        $data = UserAddress::find($request->id);
        return response(['data'=>$data , 'success'=>true]);
    }

    public function delete_address(Request $request)
    {
        $id = $request->id;
        $data = UserAddress::find($id);
        $data->delete();
        $data = UserAddress::where('user_id',auth()->user()->id)->get();
        $addressData = '';
        $addressData .= '<h4>User address</h4><hr>';
        foreach($data as $value)
        {
            $addressData .=  '<div id="address_id'.$value->id .'">';
            $addressData .= '<img src="' . url('image/icon/Icon awesome-map-marker-alt.png') . '" alt="" class="rounded-lg">';
            $addressData .= $value->soc_name;
            $addressData .= $value->street;
            $addressData .= $value->city;
            $addressData .= $value->zipcode;
            $addressData .= '<br><img src="'. url('image/icon/edit_address.png') .'" onclick="edit_address('. $value->id .')" alt="" class="edit_address">';
            $addressData .= '<img src="http://127.0.0.1:8000/image/icon/delete.png" onclick="delete_address('. $value->id .')" alt="" class="delete_address float-right">';
            $addressData .= '<hr></div>';
        }
        return response(['data'=>$addressData,'success'=>true,'message'=>'Successfully deleted..!!']);
    }

    public function add_review(Request $request)
    {
        $order_data = Order::find($request->order_id);
        if($order_data->order_status == 'Pending' && $order_data->order_status == 'Cancel')
        {
            return response(['success'=>false , 'data'=>'Can not accept review']);
        }
        else
        {
            $data = array();
            $data['message'] = $request->message;
            $data['shop_id'] = $request->shop_id;
            $data['customer_id'] = auth()->user()->id;
            $data['order_id'] = $request->order_id;
            $data['rate'] = $request->rate;
            $data['item_id'] = $order_data->items;
            $data['package_id'] = $order_data->package_id;
            $reviewData = Review::create($data);
            $reviewString = '';
            $reviewString .= '<small><i>Review: </i></small>';
            $reviewString .= '<span>'. $reviewData->message .'</span>';
            return response(['success'=>true , 'data'=>$data , 'reviewString'=>$reviewString]);
        }
    }

    public function add_grocery_review(Request $request)
    {
        $order_data = GroceryOrder::find($request->order_id);
        if($order_data->order_status == 'Pending')
        {
            return response(['success'=>false , 'data'=>'Can not accept review Order status is pending..!!']);
        }
        $data = array();
        $data['message'] = $request->message;
        $data['shop_id'] = $request->shop_id;
        $data['customer_id'] = auth()->user()->id;
        $data['order_id'] = $request->order_id;
        $data['rate'] = $request->rate;
        $data['item_id'] = $order_data->items;
        $data['package_id'] = $order_data->package_id;
        $reviewData = GroceryReview::create($data);
        $reviewString = '';
        $reviewString .= '<small><i>Review: </i></small>';
        $reviewString .= '<span>'. $reviewData->message .'</span>';
        return response(['success'=>true , 'data'=>$data ,'reviewString'=>$reviewString]);
    }

    public function update_cover_image(Request $request)
    {
        if ($request->hasFile('image'))
        {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $userImage = $name;
        }
        User::find(auth()->user()->id)->update(['cover_image'=>$userImage]);
        return response(['success'=>true]);
    }
}
