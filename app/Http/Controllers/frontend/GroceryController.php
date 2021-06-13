<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\controller;
use App\Category;
use App\CompanySetting;
use App\Currency;
use App\GroceryCategory;
use App\GroceryItem;
use App\GroceryOrder;
use App\GroceryOrderChild;
use App\Item;
use App\GroceryReview;
use App\GroceryShop;
use App\GrocerySubCategory;
use App\Notification;
use App\NotificationTemplate;
use App\Review;
use App\Setting;
use App\User;
use Carbon\Carbon;
use Session;
use OneSignal;
use Illuminate\Http\Request;

class GroceryController extends Controller
{
    public function display_grocery()
    {
        // dd(Session::get('GrocaryShop'));
        $data = GroceryItem::all()->take(5);
        $grocarries_shop = GroceryShop::with(['locationData'])->get()->take(5);
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        $grocery_categories = GroceryCategory::all();
        return view('frontend.display_grocery', compact('data', 'grocarries_shop', 'currency', 'grocery_categories'));
    }

    public function all_grocery_item()
    {
        $grocarries_item = GroceryItem::all();
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        return view('frontend.grocarries_item', compact('grocarries_item', 'currency'));
    }

    public function category_item($id)
    {
        $grocery_categories = GroceryCategory::all();
        $grocery_categories_name = GrocerySubCategory::find($id);
        $grocery_sub_categories = GrocerySubCategory::where('category_id', $id)->get();
        $cat_id = array();
        foreach ($grocery_sub_categories as $grocery_sub_category)
        {
            array_push($cat_id,$grocery_sub_category->id);
        }
        $categories_item = GroceryItem::whereIn('subcategory_id', $cat_id)->get();
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        //  dd($grocery_categories_name);
        return view('frontend.category_item', compact('grocery_categories', 'grocery_sub_categories', 'currency', 'grocery_categories_name'));
    }

    public function display_category(Request $request)
    {
        $category_name = GroceryCategory::find($request->id)->name;
        $grocery_sub_categories = GrocerySubCategory::where('category_id', $request->id)->get();
        // dd($grocery_sub_categories);
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        if (count($grocery_sub_categories) > 0)
        {
            $id = array();
            foreach ($grocery_sub_categories as $grocery_sub_category)
            {
                array_push($id,$grocery_sub_category->id);
            }
            // $sub_categories = GroceryItem::with(['subcategory'])->whereIn('subcategory_id', $id)->get();
            $grocery_item = '';
            foreach ($grocery_sub_categories as $grocery_sub_category)
            {
                
                $sub_categories = GroceryItem::with(['subcategory'])->where('subcategory_id', $grocery_sub_category->id)->get();
                if(count($sub_categories)>0){
                $grocery_item .=
                '<div class="row p-3">
                    <div class="col-md-9 col-sm-9 col-9">
                        <div class="sub_category_name text-left">
                            '. $grocery_sub_category->name .'
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-3">
                       
                    </div>
                </div>';
                }
                $grocery_item .= '<div class="row scrollContainer cartItem">';
                if(count($sub_categories)>0)
                {
                    // return $sub_categories;
                    // dd($sub_categories);
                    foreach ($sub_categories as $category_item)
                    {
                        //  return ($category_item);
                        $grocery_item .= '<div class="col-md-4 col-sm-6 col-lg-3 pb-4">';
                        $grocery_item .= '<div class="offerProduct grocery_content w-100 h-100 text-left rounded-lg bg-white p-3">';
                        $grocery_item .= '<a href="'. url('single_grocery/'.$category_item->id) .'">
                        <img src= '.url("images/upload/".$category_item->image).' height="200" width="200" alt=""
                        class="rounded-lg mb-4"></a><br>
                        <div class="t1">
                        <div class="font-weight-bold pb-5 t1">'.
                            $category_item->name .'<br>'.
                            $category_item->weight .'gm </div>';
                        if (Session::get('GrocarycartData') == null)
                        {
                            $grocery_item .= '<div class="row grocery_row"><div class="col left_col text-left">';
                            $grocery_item .= '<span class="qty">';
                            $grocery_item .= '<button onclick="add_grocery_cart(' . $category_item->id . ',' ."`minus`" . ')" class="minus">-</button>';
                            $grocery_item .= '<input type="text" value="0" id="qty' . $category_item->id . '" name="qty" readonly="readonly" disabled="disabled">';
                            $grocery_item .= '<button onclick="add_grocery_cart(' . $category_item->id . ',' . "`plus`" . ')">+</button></span></div><div class="col right_col text-green text-right">';
                            $grocery_item .= $currency .'<input type="text" id="price' . $category_item->id . '" value="' . $category_item->sell_price . '" readonly="readonly" class="price text-green"></div></div>';
                        }
                        else
                        {
                            if (in_array($category_item->id, array_column(Session::get('GrocarycartData'), 'id')))
                            {
                                foreach (Session::get('GrocarycartData') as $cartData)
                                {
                                    if ($cartData['id'] == $category_item->id)
                                    {
                                        $grocery_item .= '<div class="row grocery_row"><div class="col left_col text-left">';
                                        $grocery_item .= '<span class="qty">';
                                        $grocery_item .= '<button onclick="add_grocery_cart(' . $cartData['id'] . ',' . "`minus`" . ')" class="minus">-</button>';
                                        $grocery_item .= '<input type="text" value="' . $cartData['qty'] . '" id="qty' . $cartData['id'] . '" name="qty" readonly="readonly" disabled="disabled">';
                                        $grocery_item .= '<button onclick="add_grocery_cart(' . $cartData['id'] . ',' . "`plus`" . ')">+</button></span></div><div class="col right_col text-green text-right">';
                                        $grocery_item .= $currency.'<input type="text" id="price' . $cartData['id'] . '" value="' . $cartData['price'] . '" readonly="readonly" class="price text-green"></div></div>';
                                    }
                                }
                            }
                            else
                            {
                                $grocery_item .= '<div class="row grocery_row"><div class="col left_col text-left">';
                                $grocery_item .= '<span class="qty">';
                                $grocery_item .= '<button onclick="add_grocery_cart(' . $category_item->id . ',' . "`minus`" . ')" class="minus">-</button>';
                                $grocery_item .= '<input type="text" value="0" id="qty' . $category_item->id . '" name="qty" readonly="readonly" disabled="disabled">';
                                $grocery_item .= '<button onclick="add_grocery_cart(' . $category_item->id . ',' . "`plus`" . ')">+</button></span></div><div class="col right_col text-green text-right">';
                                $grocery_item .= $currency.'<input type="text" id="price text-green' . $category_item->id . '" value="' . $category_item->sell_price . '" readonly="readonly" class="price"></div></div>';
                            }
                        }
                        $grocery_item .= '<input type="hidden" value="' . $category_item->sell_price . '" id="original_price' . $category_item->id . '"></div></div>';
                        $grocery_item .= '</div>';
                    }
                }
                else
                {
                    // $grocery_item = '<div class="text-left">No Item Available123...</div>';
                    // return response(['success' => false, 'data' => ['groceryItem' => $grocery_item,'category_name' => $category_name]]);
                }
                $grocery_item .= '</div></div></div>';
            }
            // dd($grocery_item);
            return response(['success' => true, 'data' => ['groceryItem' => $grocery_item,'category_name' => $category_name]]);
        }
        else
        {
            $grocery_item = '<div class="sub_category_name text-left">No Item Available...</div>';
            return response(['success' => false, 'data' => ['groceryItem' => $grocery_item,'category_name' => $category_name]]);
        }
    }

    public function grocery_stores()
    {
        $grocerries_shop = GroceryShop::with(['locationData'])->get();
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        return view('frontend.grocerries_shop', compact('grocerries_shop', 'currency'));
    }

    public function show_grocery_shop($id)
    {
        $data = GroceryShop::find($id);
        $grocerries_item = GroceryItem::where('shop_id', $id)->get();
        $grocery_category = GrocerySubCategory::where('shop_id', $id)->get();
        // dd($grocery_category);
        // grocery_category
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        return view('frontend.grocery_shop', compact('data', 'grocerries_item', 'grocery_category', 'currency'));
    }

    public function grocery_filter(Request $request)
    {
        if($request->check == "top rated")
        {
            $shops = GroceryShop::all();
            $newShops = $shops->sortByDesc('rate');
            $test = $newShops->values()->all();
            $returnString = '';
            foreach ($test as $s)
            {
                $returnString .= '<div class="col-md-3 col-sm-3 col-3 text-left"><div class="offerProduct w-100 h-100 bg-white p-3">';
                $returnString .= '<a href="' . url('grocery_shop/' . $s->id) . '">';
                $returnString .= '<img src="' . url('images/upload/' . $s->image) . '" width="200"  class="mb-4" height="200" alt=""></a>';
                $returnString .= '<div class="font-weight-bold text-left">' .$s->name .'</div>';
                $returnString .= '<div class="text-left font">';
                $returnString .= $s->category;
                $returnString .= '<img src="'. url('image/Icon/icon map.png') .'" alt="" >&nbsp;';
                $returnString .= $s->locationData['name'] .'<br>';
                $returnString .= $s->address;
                $returnString .= '</div></div></div>';
            }
            return response(['data' => $returnString, 'success' => true]);
        }
        if ($request->check == "open now") {
            $shops = GroceryShop::all();
            foreach ($shops as $shop) {
                $startDate = $shop->open_time;
                $endDate = $shop->close_time;
                $check = \Carbon\Carbon::now()->setTimezone('Asia/Kolkata')->between($startDate, $endDate);
                if ($check == true) {
                    $returnString = '';
                    foreach ($shops as $s)
                    {
                        $returnString .= '<div class="col-md-3 col-sm-3 col-3 text-left"><div class="offerProduct w-100 h-100 bg-white p-3">';
                        $returnString .= '<a href="' . url('grocery_shop/' . $s->id) . '">';
                        $returnString .= '<img src="' . url('images/upload/' . $s->image) . '" width="200"  class="mb-4" height="200" alt=""></a>';
                        $returnString .= '<div class="font-weight-bold text-left">' .$s->name .'</div>';
                        $returnString .= '<div class="text-left font">';
                        $returnString .= $s->category;
                        $returnString .= '<img src="'. url('image/Icon/icon map.png') .'" alt="" >&nbsp;';
                        $returnString .= $s->locationData['name'] .'<br>';
                        $returnString .= $s->address;
                        $returnString .= '</div></div></div>';
                        // $returnString .= '<div class="col-md-3 text-center"><div class="offerProduct bg-white p-3">';
                        // $returnString .= '<a href="' . url('grocery_shop/' . $s->id) . '">';
                        // $returnString .= '<img src="' . url('images/upload/' . $s->image) . '" width="200" height="200" alt="" class="rounded-lg">';
                        // $returnString .= '</a>';
                        // $returnString .= '<div class="t1">';
                        // $returnString .= $s->name . '<br>';
                        // $returnString .= '</div></div>';
                        // $returnString .= '</div>';
                    }
                    return ['data' => $returnString, 'success' => true];
                }
            }
        }
        if ($request->check == "most popular")
        {
            $shops = GroceryShop::all();
            $newShops = $shops->sortBy('rate');
            $test = $newShops->values()->all();
            $returnString = '';
            foreach ($test as $s)
            {
                $returnString .= '<div class="col-md-3 col-sm-3 col-3 text-left"><div class="offerProduct w-100 h-100 bg-white p-3">';
                $returnString .= '<a href="' . url('grocery_shop/' . $s->id) . '">';
                $returnString .= '<img src="' . url('images/upload/' . $s->image) . '" width="200"  class="mb-4" height="200" alt=""></a>';
                $returnString .= '<div class="font-weight-bold text-left">' .$s->name .'</div>';
                $returnString .= '<div class="text-left font">';
                $returnString .= $s->category;
                $returnString .= '<img src="'. url('image/Icon/icon map.png') .'" alt="" >&nbsp;';
                $returnString .= $s->locationData['name'] .'<br>';
                $returnString .= $s->address;
                $returnString .= '</div></div></div>';
            }
            return ['data' => $returnString, 'success' => true];
        }
        if ($request->check == "arrived at shop") {
            $shops = GroceryShop::where('delivery_type', 'Shop')->get();
            foreach ($shops as $shop) {
                $returnString = '';
                foreach ($shops as $s)
                {
                    $returnString .= '<div class="col-md-3 col-sm-3 col-3 text-left"><div class="offerProduct w-100 h-100 bg-white p-3">';
                    $returnString .= '<a href="' . url('grocery_shop/' . $s->id) . '">';
                    $returnString .= '<img src="' . url('images/upload/' . $s->image) . '" width="200"  class="mb-4" height="200" alt=""></a>';
                    $returnString .= '<div class="font-weight-bold text-left">' .$s->name .'</div>';
                    $returnString .= '<div class="text-left font">';
                    $returnString .= $s->category;
                    $returnString .= '<img src="'. url('image/Icon/icon map.png') .'" alt="" >&nbsp;';
                    $returnString .= $s->locationData['name'] .'<br>';
                    $returnString .= $s->address;
                    $returnString .= '</div></div></div>';
                    // $returnString .= '<div class="col-md-3 text-center"><div class="offerProduct bg-white p-3">';
                    // $returnString .= '<a href="' . url('grocery_shop/' . $s->id) . '">';
                    // $returnString .= '<img src="' . url('image/upload/' . $s->image) . '" width="200" height="200" alt="" class="rounded-lg">';
                    // $returnString .= '</a>';
                    // $returnString .= '<div class="t1">';
                    // $returnString .= $s->name . '<br>';
                    // $returnString .= '</div></div>';
                    // $returnString .= '</div>';
                }
                return ['data' => $returnString, 'success' => true];
            }
        }
    }

    public function add_grocery_cart(Request $request)
    {
        $data = $request->all();
        // session()->forget('GrocaryShop');
        // session()->forget('GrocarycartData');
        // dd(Session::get('GrocarycartData'));
        // dd($request->qty);
        if (Session::get('cartData') == null) {
            if (Session::get('GrocaryShop') == null && Session::get('GrocarycartData')) {
                $master = array();
                $master['id'] = $request->id;
                $master['price'] = $request->price;
                $master['qty'] = 1;
                $dt = GroceryItem::where('id', $request->id)->get()->first();             
                Session::push('GrocarycartData', $master);
                Session::put('GrocaryShop', $dt->shop_id);
                return response(['success' => true, 'data' => Session::get('GrocaryShop')]);
            } else {
                $session = Session::get('GrocarycartData');            
                $grocaryItem = GroceryItem::where('id', $request->id)->get()->first();
                $original_price = GroceryItem::find($request->id)->sell_price;
                if (Session::get('GrocaryShop') == $grocaryItem->shop_id) {
                    $master = array();
                    $master['id'] = $request->id;
                    $master['price'] = $request->price;
                    $master['qty'] = 1;
                    foreach ($session as $s) {
                        if ($s['id'] != $request->id) {
                            array_push($session, $master);
                        }                    
                    }
             
                    $beforeS = $session;
                   
                    foreach ($session as $key => $item) {
                        if ($data['operation'] == "plus") {
                            if ($item['id'] == $request->id) {                   
                                $session[$key]['qty'] = $session[$key]['qty'] + $request->qty;
                                $session[$key]['price'] = $session[$key]['price'] +  $original_price;                   
                            }
                        } else {
                            if ($item['id'] == $request->id) {
                                $session[$key]['qty'] = $session[$key]['qty'] - $request->qty;
                                $session[$key]['price'] = $session[$key]['price'] -  $original_price;
                            }
                        }
                    }
                  
                    return response(['before' => $beforeS, 'after' => $session]);
                    Session::put('GrocarycartData', array_values($session));                  
                    return response(['success' => true, 'data' => Session::get('GrocaryShop'), 'gDate' => Session::get('GrocarycartData')]);
                  
                } else {
                    return response(['success' => false, 'data' => 'Shop is not same..!!']);
                }
            }
        } else {
            return response(['success' => false, 'data' => 'Food Item Available in cart..!!']);
        }
    }

    public function add_grocery_cart_v1(Request $request)
    {
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        $data = $request->all();
        $price = 0;
        $qty = 1;
        if (Session::get('cartData') == null)
        {
            if (Session::get('GrocarycartData') == null)
            {
                $master = array();
                $master['id'] = $request->id;
                $master['price'] = $request->price;
                $master['qty'] = 1;
                $dt = GroceryItem::where('id', $request->id)->get()->first();
                Session::push('GrocarycartData', $master);
                Session::put('GrocaryShop', $dt->shop_id);
                $final_price = 0;
                foreach (Session::get('GrocarycartData') as $value)
                {
                    $final_price += intval($value['price']);
                }
                return response(['success' => true, 'data' => ['price' => $request->price, 'qty' => 1,'final_price'=>$final_price], 'message' => "Data Inserted SuccessFully..!!"]);
            }
            else
            {
                $session = Session::get('GrocarycartData');
                $grocaryItem = GroceryItem::where('id', $request->id)->get()->first();
                $original_price = GroceryItem::find($request->id)->sell_price;
                $price = $original_price;
                if (Session::get('GrocaryShop') == $grocaryItem->shop_id)
                {
                    if (in_array($request->id, array_column(Session::get('GrocarycartData'), 'id'))) {
                        foreach ($session as $key => $value) {
                            if ($session[$key]['id'] == $request->id)
                            {
                                if ($data['operation'] == "plus")
                                {
                                    $qty = $session[$key]['qty'] + $request->qty;
                                    $price = $session[$key]['price'] +  $original_price;
                                    $session[$key]['qty'] = $session[$key]['qty'] + $request->qty;
                                    $session[$key]['price'] = $session[$key]['price'] +  $original_price;
                                }
                                else
                                {
                                    if (intval($session[$key]['qty']) > 1)
                                    {
                                        $qty = $session[$key]['qty'] - $request->qty;
                                        $price = $session[$key]['price'] - $original_price;
                                        $session[$key]['qty'] = $session[$key]['qty'] - $request->qty;
                                        $session[$key]['price'] = $session[$key]['price'] - $original_price;
                                    }
                                }
                            }
                        }
                    } else {
                        $master = array();
                        $master['id'] = $request->id;
                        $master['price'] = $request->price;
                        $master['qty'] = 1;
                        array_push($session, $master);
                    }
                    Session::put('GrocarycartData', array_values($session));
                    $shop_id = Session::get('GrocaryShop');
                    $shop = GroceryShop::find($shop_id);
                 
                    $final_price = 0;
                    $discount = 0;                    
                    foreach (Session::get('GrocarycartData') as $value)
                    {
                        $final_price += intval($value['price']);
                    }                   
                    if (Session::get('coupenCode') != null)
                    {
                        $code = Session::get('coupenCode');
                        if ($code->type == 'amount')
                        {
                            $discount = $code->discount;                       
                            if(isset($shop->rastaurant_charge)) {
                                $to_pay = ($final_price + $shop->rastaurant_charge);
                            } else {
                                $to_pay = $final_price;
                            }
                        }
                        if ($code->type == 'percentage')
                        {
                            if(Session::get('cartData') != null)
                            {
                                $fp = intval($final_price + $shop->rastaurant_charge);
                                // $to_pay = $final_price ;
                            }
                            if(Session::get('GrocarycartData') != null)
                            {
                                $fp = intval($final_price);
                                // $to_pay = $final_price;
                            }
                            $to_pay = $fp;
                            $discount = $code->discount;                           
                        }
                        return response(
                            [
                                'data' => [
                                    'price' => $price,
                                    'qty' => $qty,
                                    'currency' => $currency ,
                                    'to_pay'=>$to_pay,
                                    'discount' => $discount,
                                    'final_price' => $final_price,
                                    'discountType' => $code->type
                                ],
                                'success' => true,
                                'message' => "Data Inserted SuccessFully..!!"
                            ]

                            );
                    }
                    // dd($final_price);
                    if(Session::get('cartData') != null)
                    {
                        $to_pay = $final_price + $shop->rastaurant_charge;
                    }
                    if(Session::get('GrocarycartData') != null)
                    {
                        $to_pay = $final_price;
                    }
                    // dd($price);
                    if($data['operation']=="plus"){
                        return response(['data' => ['price' => $price, 'qty' => $qty,'final_price' => $final_price, 'to_pay' => $to_pay, 'currency' => $currency, 'delivery_charge'=> $shop->delivery_charge], 'success' => true, 'message' => "Data Inserted SuccessFully..!!"]);
                    }
                    else{
                        return response(['data' => ['price' => $price, 'qty' => $qty,'final_price' => $final_price, 'to_pay' => $to_pay, 'currency' => $currency, 'delivery_charge'=> $shop->delivery_charge], 'success' => true, 'message' => "Data Deleted SuccessFully..!!"]);
                    }
                    
                } else {
                    return response(['data' => 'Shop is not same..!']);
                }
            }
        } else {
            return response(['success' => false, 'data' => 'Food Item Available in cart..!!']);
        }
    }

    public function single_grocery_profile($id)
    {
        $data = User::all();
        $groceryitem = GroceryItem::find($id);
        $grocery_shop_review = Groceryreview::where('shop_id',$groceryitem->shop_id)->get();
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        return view('frontend.single_grocery', compact('data','groceryitem', 'currency','grocery_shop_review'));
    }

    public function single_food_profile($id)
    {
        $data = User::all();
        $item = Item::find($id);        
        $shop_review = Review::where('shop_id',$item->shop_id)->get();
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        return view('frontend.single_food', compact('data','item', 'currency','shop_review'));
    }

    public function cancel_grocery_order($id)
    {
        GroceryOrder::find($id)->update(array('order_status' => 'Cancel'));
        $web_notification = Setting::where('web_notification', 1)->get()->first();
        $notification = NotificationTemplate::where('title', 'Order Status')->get()->first();
        $notification = $notification->message_content;
        $shop_name = CompanySetting::find(1)->name;
        $s = GroceryOrder::find($id)->shop_id;
        $order_no = GroceryOrder::find($id)->order_no;
        $status = GroceryOrder::find($id)->order_status;
        $shop = GroceryShop::find($s)->name;
        $detail['name'] = auth()->user()->name;
        $detail['order_no'] = $order_no;
        $detail['shop'] = $shop;
        $detail['status'] = $status;
        $detail['shop_name'] = $shop_name;
        $data = ["{{name}}", "{{order_no}}" ,"{{shop}}","{{status}}", "{{shop_name}}"];
        $message1 = str_replace($data, $detail, $notification);
        $url = url('/user_details');
        if($web_notification != null)
        {
            if ($web_notification->web_notification == 1)
            {
                $user_notification = User::where('id', auth()->user()->id)->get()->first();
                if ($user_notification->enable_notification == 1)
                {
                    if (auth()->user()->device_token != null)
                    {
                        try{
                        OneSignal::sendNotificationToUser(
                            $message1,
                            $userId = auth()->user()->device_token,
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
            }
        }
        $item = [];
        $item['user_id'] = auth()->user()->id;
        $item['order_id'] = $id;
        $item['title'] = 'Order Status';
        $item['message'] = $message1;
        $item['notification_type'] = 'Grocery';
        Notification::create($item);
        return redirect('user_details');
    }

    public function search_grocery(Request $request)
    {
        $shop = GroceryShop::find($request->id)->id;        
        $item = GroceryItem::where([['shop_id', $shop], ['name', 'LIKE', "%{$request->value}%"]])->get();        
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        if (count($item) > 0) {
            $grocery_string = '';
            foreach ($item as $value)
            {
                $grocery_string .= '<div class="col-md-4 col-sm-6 col-lg-3 pb-4"><div class="offerProduct grocery_content w-100 h-100 text-left rounded-lg bg-white p-3">';
                $grocery_string .= '<img src="' . url('images/upload/' . $value->image) . '" width="200" class="mb-4 rounded-lg" height="200" alt=""><br><div class="offerproduct"><div class="t1">';
                $grocery_string .= '<div class="t1"><div class="font-weight-bold pb-5">'.$value->name .'<br>';
                $grocery_string .= $value->weight . 'gm<br></div>';
                if (Session::get('GrocarycartData') == null)
                {
                    $grocery_string .= '<div class="row grocery_row"><div class="col left_col text-left">';
                    $grocery_string .= '<span class="qty">';
                    $grocery_string .= '<button onclick="add_grocery_cart(' . $value->id . ',' . "`minus`" . ')" class="minus">-</button>';
                    $grocery_string .= '<input type="text" value="0" id="qty' . $value->id . '" name="qty" readonly="readonly" disabled="disabled">';
                    $grocery_string .= '<button onclick="add_grocery_cart(' . $value->id . ',' . "`plus`" . ')">+</button></span></div><div class="col text-green right_col text-right">';
                    $grocery_string .= $currency .'<input type="text" id="price' . $value->id . '" value="' . $value->sell_price . '" readonly="readonly" class="price text-green"></div>';
                } else {
                    if (in_array($value->id, array_column(Session::get('GrocarycartData'), 'id')))
                    {
                        foreach (Session::get('GrocarycartData') as $cartData)
                        {
                            if ($cartData['id'] == $value->id)
                            {
                                $grocery_string .= '<div class="row grocery_row"><div class="col left_col text-left">';
                                $grocery_string .= '<span class="qty">';
                                $grocery_string .= '<button onclick="add_grocery_cart(' . $cartData['id'] . ',' . "`minus`" . ')" class="minus">-</button>';
                                $grocery_string .= '<input type="text" value="0" id="qty ' . $cartData['id'] . ' " name="qty" readonly="readonly" disabled="disabled">';
                                $grocery_string .= '<button onclick="add_grocery_cart(' . $cartData['id'] . ',' . "`plus`" . ')">+</button></span></div><div class="col right_col text-right">';
                                $grocery_string .= $currency .'<input type="text" id="price' . $cartData['id'] . '" value="' . $cartData['price'] . '" readonly="readonly" class="price"></div>';
                            }
                        }
                    }
                    else
                    {
                        $grocery_string .= '<div class="row grocery_row"><div class="col left_col text-left">';
                        $grocery_string .= '<span class="qty">';
                        $grocery_string .= '<button onclick="add_grocery_cart(' . $value->id . ',' . "`minus`" . ')" class="minus">-</button>';
                        $grocery_string .= '<input type="text" value="0" id="qty ' . $value->id . '" name="qty" readonly="readonly" disabled="disabled">';
                        $grocery_string .= '<button onclick="add_grocery_cart(' . $value->id . ',' . "`plus`" . ')">+</button></span></div><div class="col right_col text-right">';
                        $grocery_string .= $currency .'<input type="text" id="price' . $value->id . '" value="' . $value->sell_price . '" readonly="readonly" class="price"></div>';
                    }
                }
                $grocery_string .= '<input type="hidden" value="' . $value->sell_price . '" id="original_price' . $value->id . '"></div></div></div></div></div></div>';                
            }
            return response(['success' => true, 'data' => $grocery_string]);
        } else {
            return response(['success' => false, 'data' => "Oopss..Items not Found search again..!!"]);
        }
    }

    public function grocery_store_search(Request $request)
    {
        $shops = GroceryShop::where('name', 'LIKE', "%{$request->value}%")->get();
        if (count($shops) > 0)
        {
            $shop_search = '';
            foreach ($shops as $shop) {
                $shop_search .= '<div class="col-md-3 col-sm-3 col-3 text-left"><div class="offerProduct w-100 h-100 bg-white p-3">';
                $shop_search .= '<a href="' . url('grocery_shop/' . $shop->id) . '">';
                $shop_search .= '<img src="' . url('images/upload/' . $shop->image) . '" width="200"  class="mb-4" height="200" alt=""></a>';
                $shop_search .= '<div class="font-weight-bold text-left">' .$shop->name .'</div>';
                $shop_search .= '<div class="text-left font">';
                $shop_search .= $shop->category;
                $shop_search .= '<img src="'. url('image/Icon/icon map.png') .'" alt="">';
                $shop_search .= $shop->locationData['name'] .'<br>';
                $shop_search .= $shop->address;
                $shop_search .= '</div></div></div>';
            }
            return response(['success' => true, 'data' => $shop_search]);
        } else {
            return response(['success' => false, 'data' => "Oopss..Items not Found search again..!!"]);
        }
    }

    public function grocery_item_search(Request $request)
    {
        $Items = GroceryItem::where('name', 'LIKE', "%{$request->value}%")->get();
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        if (count($Items) > 0) {
            $grocery_string = '';
            foreach ($Items as $value)
            {
                $grocery_string .= '<div class="col-md-4 col-sm-6 col-lg-3"><div class="offerProduct  grocery_content w-100 h-100 text-left rounded-lg bg-white p-3">';
                $grocery_string .= '<a href="'. url('single_grocery/'.$value->id) .'">';
                $grocery_string .= '<img src="' . url('images/upload/' . $value->image) . '" width="200"  class="mb-4"  height="200" alt=""><br></a><div class="offerproduct"><div class="t1">';
                $grocery_string .= '<div class="font-weight-bold pb-5">'.$value->name;
                $grocery_string .= '<br>' . $value->weight . 'gm<br></div>';
                if (Session::get('GrocarycartData') == null)
                {
                    // dd("hello");
                    $grocery_string .= '<div class="row grocery_row"><div class="col left_col text-left"><span class="qty">';
                    $grocery_string .= '<button onclick="add_grocery_cart(' . $value->id . ',' . "`minus`" . ')" class="minus">-</button>';
                    $grocery_string .= '<input type="text" value="0" id="qty' . $value->id . '" name="qty" readonly="readonly" disabled="disabled">';
                    $grocery_string .= '<button onclick="add_grocery_cart(' . $value->id . ',' . "`plus`" . ')">+</button></span></div>
                    <div class="col right_col text-right text-green">';
                    $grocery_string .= $currency.'<input type="text" id="price' . $value->id . '" value="' . $value->sell_price . '" readonly="readonly" class="price text-green"></div></div>';
                }
                else
                {                    
                    if (in_array($value->id, array_column(Session::get('GrocarycartData'), 'id')))
                    {
                        foreach (Session::get('GrocarycartData') as $cartData)
                        {                            
                            if ($cartData['id'] == $value->id)
                            {
                                $grocery_string .= '<div class="row grocery_row"><div class="col left_col text-left"><span class="qty">';
                                $grocery_string .= '<button onclick="add_grocery_cart(' . $cartData->id . ',' . "`minus`" . ')" class="minus">-</button>';
                                $grocery_string .= '<input type="text" value="0" id="qty ' . $cartData->id . ' " name="qty" readonly="readonly" disabled="disabled">';
                                $grocery_string .= '<button onclick="add_grocery_cart(' . $cartData->id . ',' . "`plus`" . ')">+</button></span></div>
                                <div class="col right_col text-right text-green">';
                                $grocery_string .= $currency.'<input type="text" id="price' . $cartData->id . '" value="' . $cartData->price . '" readonly="readonly" class="price"></div></div>';
                            }
                        }
                    }
                    else
                    {
                        $grocery_string .= '<div class="row grocery_row"><div class="col left_col text-left"><span class="qty">';
                        $grocery_string .= '<button onclick="add_grocery_cart(' . $value->id . ',' . "`minus`" . ')" class="minus">-</button>';
                        $grocery_string .= '<input type="text" value="0" id="qty ' . $value->id . '" name="qty" readonly="readonly" disabled="disabled">';
                        $grocery_string .= '<button onclick="add_grocery_cart(' . $value->id . ',' . "`plus`" . ')">+</button></span></div><div class="col right_col text-right text-green">';
                        $grocery_string .= $currency.'<input type="text" id="price' . $value->id . '" value="' . $value->sell_price . '" readonly="readonly" class="price text-green"></div></div>';
                    }
                }
                $grocery_string .= '<input type="hidden" value="' . $value->sell_price . '" id="original_price' . $value->id . '"></div></div></div></div>';                
            }
            return response(['success' => true, 'data' => $grocery_string]);
        } else {
            return response(['success' => false, 'data' => "Oopss..Items not Found search again. !!"]);
        }
    }

    public function remove_coupen()
    {
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        session()->forget('coupenCode');
        $finalWithResturantCharge = 0;
        if(Session::get('cartData') != null)
        {
            $final_price = 0;
            foreach (Session::get('cartData') as $value)
            {
                $final_price += intval($value['price']);
            }
            $shop = Session::get('shop');
            $finalWithResturantCharge = $shop->rastaurant_charge;
            // $shop = GroceryShop::find($id);
            $delivery_charge = $shop->delivery_charge;
            return response(['success'=>true , 'data'=>[ 'currency'=>$currency , 'final_price' => $final_price , 'finalWithResturantCharge' =>$finalWithResturantCharge , 'deilvery_charge' => $delivery_charge]]);
        }
        if(Session::get('GrocarycartData') != null)
        {
            $final_price = 0;
            foreach (Session::get('GrocarycartData') as $value)
            {
                $final_price += intval($value['price']);
            }
            $id = Session::get('GrocaryShop');
            $shop = GroceryShop::find($id);
            $delivery_charge = $shop->delivery_charge;            
            return response(['success'=>true , 'data'=>[ 'currency'=>$currency , 'final_price' => $final_price , 'deilvery_charge' => $delivery_charge]]);
        }
    }
}
