<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\controller;
use App\Category;
use App\CompanySetting;
use App\Coupon;
use App\Currency;
use App\GroceryItem;
use App\GroceryOrder;
use App\GroceryOrderChild;
use App\GroceryShop;
use App\Item;
use App\Notification;
use App\NotificationTemplate;
use App\Order;
use App\OrderChild;
use App\Package;
use App\PaymentSetting;
use App\Review;
use App\Setting;
use Session;
use App\Shop;
use App\User;
use App\UserAddress;
use Carbon\Carbon;
use Stripe;
use Illuminate\Http\Request;
use DB;
use KingFlamez\Rave\Facades\Rave;
use OneSignal;
use Paystack;
use PaytmWallet;

class DisplayController extends Controller
{
    public function index()
    {
        $shops = Shop::all();
        $offers = Package::all()->take(5);
        $grocarries = GroceryItem::with(['category', 'subcategory'])->get()->take(5);
        $grocarries_shop = GroceryShop::with(['locationData'])->get()->take(5);
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        return view('frontend.index', compact('shops', 'offers', 'grocarries', 'grocarries_shop', 'currency'));
    }

    public function food_display()
    {
        $shops = Shop::all()->take(5);
        $offers = Package::all();
        $categoris = Category::all();
        $foods = Item::where('isPopular', 1)->get();
        $pocket_friendly = Item::orderBy('price', 'asc')->get();
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        return view('frontend.food_display', compact('shops', 'offers', 'categoris', 'foods', 'pocket_friendly', 'currency'));
    }

    public function all_rest()
    {
        $shops = Shop::all();
        return view('frontend.all_rest', compact('shops'));
    }

    public function offer()
    {
        $offers = Package::all();
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        return view('frontend.offer', compact('offers', 'currency'));
    }

    public function restaurants_information($id)
    {
        $data = shop::find($id);
        $data['item'] = Item::with('shop')->where('shop_id', $id)->get();
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        $data['combo'] = Package::where('shop_id', $id)->get();

        return view('frontend.restaurants_information', compact('data', 'currency'));
    }

    public function add_cart(Request $request)
    {
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        if (Session::get('GrocarycartData') == null) {
            if (Session::get('cartData') == null) {
                $master = array();
                $master['price'] = $request->price;
                $master['type'] = $request->type;
                $master['qty'] = 1;
                if ($master['type'] == 'item') {
                    $dt = Item::where('id', $request->id)->get()->first();
                    $shop = Shop::where('id', $dt->shop_id)->get()->first();
                    $master['id'] = $request->id;
                } else {
                    $dt = Package::where('id', $request->id)->get()->first();
                    $shop = Shop::where('id', $dt->shop_id)->get()->first();
                    $master['combo_id'] = $request->id;
                }
                Session::push('cartData', $master);
                Session::put('shop', $shop);
                $cartString = '';
                foreach (Session::get('cartData') as $value) {
                    if (isset($value['id']) && $value['id'] == $request->id) {
                        if ($request->type == 'item') {
                            $Item_price = Item::find($value['id'])->price;
                            $cartString .= '<span class="text-green rest_price">';
                            $cartString .= $currency;
                            $cartString .= '<input type="text" id="price' . $value['id'] . '" value="' . $value['price'] . '" readonly="readonly" class="price text-green"></span>';
                            $cartString .= '<span class="qty float-right mt-5"><button class="minus" onclick="update_cart(' . $value['id'] . ',' . "`item`" . ',' . "`minus`" . ')">-</button>';
                            $cartString .= '<input type="text" value="' . $value['qty'] . '" id="qty' . $value['id'] . '" name="qty" readonly="readonly" disabled="disabled">';
                            $cartString .= '<button onclick="update_cart(' . $value['id'] . ',' . "`item`" . ',' . "`plus`" . ')">+</button></span>';
                            $cartString .= '<input type="hidden" value="' . $Item_price . '" id="original_price' . $value['id'] . '">';
                        }
                    }
                    if (isset($value['combo_id'])) {
                        if ($request->type == 'combo') {
                            $Item_price = Package::find($value['combo_id'])->package_price;
                            $cartString .= '<span class="text-green rest_price">';
                            $cartString .= $currency;
                            $cartString .= '<input type="text" id="price' . $value['combo_id'] . '" value="' . $value['price'] . '" readonly="readonly" class="price text-green"></span><br>';
                            $cartString .= '<span class="qty float-right mt-5"><button class="minus"  onclick="update_cart(' . $value['combo_id'] . ',' . "`combo`" . ',' . "`minus`" . ')">-</button>';
                            $cartString .= '<input type="text" value="' . $value['qty'] . '" id="qty' . $value['combo_id'] . '" name="qty" readonly="readonly" disabled="disabled">';
                            $cartString .= '<button onclick="update_cart(' . $value['combo_id'] . ',' . "`combo`" . ',' . "`plus`" . ')">+</button></span>';
                            $cartString .= '<input type="hidden" value="' . $Item_price . '" id="original_price' . $value['combo_id'] . '">';
                        }
                    }
                }
                return ['data' => $cartString, 'success' => true];
            } else {
                $data = $request->all();
                $session = Session::get('cartData');
                if ($data['type'] == 'item') {
                    $item = Item::find($request->id);
                    $original_price = $item->price;
                } else {
                    $item = Package::find(intval($request->id));
                    $original_price = $item->package_price;
                }
                if (Session::get('shop')['id'] == $item->shop_id) {
                    if ($data['type'] == 'item') {
                        if (in_array($request->id, array_column(Session::get('cartData'), 'id'))) {
                            foreach ($session as $key => $value) {
                                if (isset($session[$key]['id'])) {
                                    $k = intval($session[$key]['id']);
                                    if ($k == $request->id) {
                                        $session[$key]['qty'] = $request->qty;
                                        $session[$key]['price'] = $session[$key]['price'] + $original_price;
                                    }
                                }
                                if ($data['type'] == 'minus') {
                                    if (isset($session[$key]['id'])) {
                                        $k = intval($session[$key]['id']);
                                        if ($k == $request->id) {
                                            if ($request->qty == 1) {
                                                $session[$key]['qty'] = $request->qty;
                                                $session[$key]['price'] = $original_price;
                                            } else {
                                                $session[$key]['qty'] = $request->qty;
                                                $session[$key]['price'] = $session[$key]['price'] -  $original_price;
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            $master = array();
                            $master['price'] = $request->price;
                            $master['qty'] = 1;
                            $master['type'] = $request->type;
                            $master['id'] = $request->id;
                            array_push($session, $master);
                        }
                        Session::put('cartData', array_values($session));
                        $cartString = '';
                        foreach (Session::get('cartData') as $value) {
                            if (isset($value['id']) && $value['id'] == $request->id) {
                                $Item_price = Item::find($value['id'])->price;
                                $cartString .= '<span class="text-green rest_price">';
                                $cartString .= $currency;
                                $cartString .= '<input type="text" id="price' . $value['id'] . '" value="' . $value['price'] . '" readonly="readonly" class="price text-green"></span>';
                                $cartString .= '<span class="qty float-right mt-5"><button class="minus" onclick="update_cart(' . $value['id'] . ',' . "`item`" . ',' . "`minus`" . ')">-</button>';
                                $cartString .= '<input type="text" value="' . $value['qty'] . '" id="qty' . $value['id'] . '" name="qty" readonly="readonly" disabled="disabled">';
                                $cartString .= '<button onclick="update_cart(' . $value['id'] . ',' . "`item`" . ',' . "`plus`" . ')">+</button></span>';
                                $cartString .= '<input type="hidden" value="' . $Item_price . '" id="original_price' . $value['id'] . '">';
                            }
                        }
                        return ['data' => $cartString, 'success' => true];
                    } else {
                        if (in_array($request->id, array_column(Session::get('cartData'), 'combo_id'))) {
                            foreach ($session as $key => $value) {
                                if ($data['type'] == 'plus') {
                                    if (isset($session[$key]['combo_id'])) {
                                        $k = intval($session[$key]['combo_id']);
                                        if ($k == $request->id) {
                                            $session[$key]['qty'] = $request->qty;
                                            $session[$key]['price'] = $session[$key]['price'] + $original_price;
                                        }
                                    }
                                }
                                if ($data['type'] == 'minus') {
                                    if (isset($session[$key]['combo_id'])) {
                                        $k = intval($session[$key]['combo_id']);
                                        if ($k == $request->id) {
                                            if ($request->qty == 1) {
                                                $session[$key]['qty'] = $request->qty;
                                                $session[$key]['price'] = $original_price;
                                            } else {
                                                $session[$key]['qty'] = $request->qty;
                                                $session[$key]['price'] = $session[$key]['price'] -  $original_price;
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            $master = array();
                            $master['price'] = $request->price;
                            $master['qty'] = 1;
                            $master['type'] = $request->type;
                            $master['combo_id'] = $request->id;
                            array_push($session, $master);
                        }
                        Session::put('cartData', array_values($session));
                        $cartString = '';
                        foreach (Session::get('cartData') as $value) {
                            if (isset($value['combo_id']) && $value['combo_id'] == $request->id) {

                                $Item_price = Package::find($value['combo_id'])->package_price;
                                $cartString .= '<span class="text-green rest_price">';
                                $cartString .= $currency;
                                $cartString .= '<input type="text" id="price' . $value['combo_id'] . '" value="' . $value['price'] . '" readonly="readonly" class="price text-green"></span><br>';
                                $cartString .= '<span class="qty float-right mt-5"><button class="minus"  onclick="update_cart(' . $value['combo_id'] . ',' . "`combo`" . ',' . "`minus`" . ')">-</button>';
                                $cartString .= '<input type="text" value="' . $value['qty'] . '" id="qty' . $value['combo_id'] . '" name="qty" readonly="readonly" disabled="disabled">';
                                $cartString .= '<button onclick="update_cart(' . $value['combo_id'] . ',' . "`combo`" . ',' . "`plus`" . ')">+</button></span>';
                                $cartString .= '<input type="hidden" value="' . $Item_price . '" id="original_price' . $value['combo_id'] . '">';
                            }
                        }
                        return ['data' => $cartString, 'success' => true];
                    }
                } else {
                    return response(['data' => 'Shop is not same..!']);
                }
            }
        } else {
            return ['data' => "Grocery Item exists in cart..", 'success' => false];
        }
    }

    public function restaurant_profile($id)
    {
        $data = Shop::find($id);
        $data['item'] = Item::where('shop_id', $id)->get();
        $reviews = Review::where('shop_id', $id)->get();
        return view('frontend.restaurant_profile', compact('data', 'reviews'));
    }

    public function order_list()
    {
        // session()->forget('cartData');
        // session()->forget('shop');
        // session()->forget('GrocaryShop');
        // session()->forget('GrocarycartData');
        // dd(Session::get('cartData'));
        // if (Session::get('cartData') == null && Session::get('GrocarycartData') == null)
        // {
        //     return view('order_list', compact('currency'));
        // } else if (Session::get('cartData') != null) {
        //     $currency_code = Setting::find(1)->currency;
        //     $currency = Currency::where('code', $currency_code)->first()->symbol;
        //     return view('order_list', compact('currency'));
        // }
        // if (Session::get('GrocarycartData'))
        // {
        //     $currency_code = Setting::find(1)->currency;
        //     $currency = Currency::where('code', $currency_code)->first()->symbol;
        //     return view('order_list', compact('currency'));
        // }
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        return view('frontend.order_list', compact('currency'));
    }

    public function remove_cart()
    {
        session()->forget('cartData');
        session()->forget('shop');
        session()->forget('GrocaryShop');
        session()->forget('GrocarycartData');
        session()->forget('coupenCode');
        return view('frontend.remove_cart');
    }

    public function category_shop($id)
    {
        $items = Item::where('category_id', $id)->get();
        $shops_id = array();
        foreach ($items as $item) {
            array_push($shops_id, $item->shop_id);
        }
        $shops = Shop::whereIn('id', $shops_id)->get();
        return view('frontend.category_shop', compact('shops'));
    }
    public function update_cart(Request $request)
    {
        $data = $request->all();
        $shop = Session::get('shop');
        $session = Session::get('cartData');
        if ($data['type'] == 'item') {
            $i = Item::find($request->id);
            $original_price = $i->price;
        } else {
            $i = Package::find($request->id);
            $original_price = $i->package_price;
        }
        foreach ($session as $key => $item) {
            if ($data['operation'] == 'plus') {
                if ($data['type'] == 'item') {
                    if (isset($session[$key]['id'])) {
                        $k = intval($session[$key]['id']);
                        if ($k == $request->id) {
                            $session[$key]['qty'] = $request->qty;
                            $session[$key]['price'] = $session[$key]['price'] + $original_price;
                        }
                    }
                }
                if ($data['type'] == 'combo') {
                    if (isset($session[$key]['combo_id'])) {
                        $k = intval($session[$key]['combo_id']);
                        if ($k == $request->id) {
                            $session[$key]['qty'] = $request->qty;
                            $session[$key]['price'] = $session[$key]['price'] + $original_price;
                        }
                    }
                }
            } else {
                if ($data['type'] == 'item') {
                    if (isset($session[$key]['id'])) {
                        $k = intval($session[$key]['id']);
                        if ($k == $request->id) {
                            if ($request->qty == 1) {
                                $session[$key]['qty'] = $request->qty;
                                $session[$key]['price'] = $original_price;
                            } else {
                                $session[$key]['qty'] = $request->qty;
                                $session[$key]['price'] = $session[$key]['price'] -  $original_price;
                            }
                        }
                    }
                } else {
                    if (isset($session[$key]['combo_id'])) {
                        $k = intval($session[$key]['combo_id']);
                        if ($k == $request->id) {
                            if ($request->qty == 1) {
                                $session[$key]['qty'] = $request->qty;
                                $session[$key]['price'] = $original_price;
                            } else {
                                $session[$key]['qty'] = $request->qty;
                                $session[$key]['price'] = $session[$key]['price'] -  $original_price;
                            }
                        }
                    }
                }
            }
        }
        Session::put('cartData', array_values($session));
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        $price = 0;
        $discount = 0;
        foreach (Session::get('cartData') as $item)
        {
            $price += intval($item['price']);
        }
        if (Session::get('coupenCode') != null) {
            $code = Session::get('coupenCode');
            $discount = $code->discount;
            if ($code->type == 'amount')
            {
                $to_pay = $price;
            }
            if ($code->type == 'percentage')
            {
                $to_pay = intval($price);
            }
            return ['message' => 'cart updated successfully!', 'data' => ['price' => $price, 'discountType' => $code->type, 'to_pay' => $to_pay, 'use_for' => $code->use_for, 'discount' => $discount, 'currency' => $currency], 'success' => true];
        }
        $to_pay = $price + $shop->rastaurant_charge;
        return ['message' => 'cart updated successfully!', 'data' => ['price' => $price, 'to_pay' => $to_pay, 'discount' => $discount, 'currency' => $currency], 'success' => true];
    }

    public function order_confirmation()
    {
        if (Session::get('cartData') == null  && Session::get('GrocarycartData') == null) {
            return redirect('user_details');
        }
        $payment_setting = PaymentSetting::first();
        if (Session::get('cartData') != null) {
            $cart = Session::get('cartData');
            $shop = Session::get('shop');
            $coupon = Coupon::all();
            $data = UserAddress::where('user_id', auth()->user()->id)->get();
            $currency_code = Setting::find(1)->currency;
            $currency = Currency::where('code', $currency_code)->first()->symbol;
            $user_address = UserAddress::where('user_id', auth()->user()->id)->get()->first();
            return view('frontend.order_confirmation', compact('data','payment_setting','cart', 'shop', 'currency', 'coupon', 'user_address'));
        } else {
            $cart = Session::get('GrocarycartData');
            $shop_id = Session::get('GrocaryShop');
            $shop = GroceryShop::find($shop_id);
            $coupon = Coupon::all();
            $data = UserAddress::where('user_id', auth()->user()->id)->get();
            $currency_code = Setting::find(1)->currency;
            $currency = Currency::where('code', $currency_code)->first()->symbol;
            $user_address = UserAddress::where('user_id', auth()->user()->id)->get()->first();
            return view('frontend.grocery_order_confirmation', compact('data','payment_setting', 'cart', 'shop', 'currency', 'coupon', 'user_address'));
        }
    }

    public function apply_coupen(Request $request)
    {
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        $code = DB::table('coupon')->where('code', $request->coupen_value)->first();
        if ($code == null) {
            return ['data' => 'coupon is invalid..!'];
        }
        else
        {
            if ($code->use_for == $request->use_for)
            {
                if ($code->use_count < $code->max_use)
                {
                    $count = $code->use_count + 1;
                    Session::put('coupenCode', $code);
                    DB::table('coupon')->where('code', $request->coupen_value)->update(['use_count' => $count]);
                    if (Session::get('GrocarycartData') != null)
                    {
                        $shop_id = Session::get('GrocaryShop');
                        $shop = GroceryShop::find($shop_id);
                    }
                    else
                    {
                        $shop = Session::get('shop');
                    }
                    if ($shop->id == $code->shop_id) {
                        $to_pay = array();
                        $to_pay['code_discount'] = $code->discount;
                        if ($code->type == 'amount') {
                            // $to_pay['final'] = ($request->price + $shop->delivery_charge) - $code->discount;
                            // $to_pay['saved_price'] = $code->discount;
                            // $to_pay['coupen_id'] = $code->id;
                            if (Session::get('GrocarycartData') != null) {
                                $fp = intval($request->price);
                            }
                            if (Session::get('cartData') != null) {
                                // $fp = intval($request->price + $shop->rastaurant_charge);
                                $fp = intval($request->price);
                            }
                            // dd($fp);
                            // dd($request->price);
                            // $to_pay['code_discount'] = round(($fp * $code->discount)/100);
                            $to_pay['final'] = $fp;
                            $to_pay['discount'] = $code->discount;
                            $to_pay['coupen_id'] = $code->id;
                            return ['data' => $to_pay, 'use_for' => $code->use_for, 'discountType' => $code->type, 'currency' => $currency, 'success' => true];
                        }
                        if ($code->type == 'percentage')
                        {
                            if (Session::get('GrocarycartData') != null) {
                                $fp = intval($request->price);
                            }
                            if (Session::get('cartData') != null) {
                                $fp = intval($request->price);
                            }
                            $to_pay['final'] = $fp;
                            $to_pay['discount'] = $code->discount;
                            $to_pay['coupen_id'] = $code->id;
                            return ['success' => true, 'data' => $to_pay, 'use_for' => $code->use_for, 'discountType' => $code->type, 'currency' => $currency];
                        }
                    } else {
                        return ['data' => 'This Coupon is not available for this shop', 'success' => false];
                    }
                } else {
                    return ['data' => 'Coupon is expire..!!', 'success' => false];
                }
            } else {
                return ['data' => 'This Coupon is not valid for this item..!!', 'success' => false];
            }
        }
    }

    public function frontendStripePayment(Request $request)
    {
        $currency = Setting::find(1)->currency;
        $stripe_secret = PaymentSetting::find(1)->stripeSecretKey;
        Stripe\Stripe::setApiKey($stripe_secret);
        $stripeDetail = Stripe\Charge::create([
            "amount" => 100 * 100,
            "currency" => $currency,
            "source" => $request->stripeToken,
        ]);
        if ($stripeDetail) {
            return response(['data' => $stripeDetail->id, 'success' => true]);
        } else {
            return response(['data' => null, 'success' => false]);
        }
    }

    public function confirm_order(Request $request)
    {
        $address_id = UserAddress::where('user_id', auth()->user()->id)->get();
        if (count($address_id) > 0) {
            $data = $request->all();
            if (Session::get('shop') != null)
            {
                $shop = Session::get('shop');
                $data['shop_charge'] = $shop->rastaurant_charge;
            }
            if (Session::get('GrocaryShop') != null) {
                $shop_id = Session::get('GrocaryShop');
                $shop = GroceryShop::find($shop_id);
            }
            $data['shop_id'] = $shop->id;
            $data['order_no'] = '#' . rand(100000, 999999);
            $data['driver_otp'] = rand(100000, 999999);
            if ($request->delivery_type == 'pick up') {
                $data['delivery_charge'] = 00;
            } else {
                $data['delivery_charge'] = $shop->delivery_charge;
            }
            $data['time'] = Carbon::now('Asia/Kolkata')->format('h:i A');
            $data['date'] = Carbon::now()->format('Y-m-d');
            $data['owner_id'] = $shop->user_id;
            $data['customer_id'] = auth()->user()->id;
            $data['location_id'] = $shop->location;
            $data['order_status'] = 'Pending';
            $items = [];
            $combo = [];
            if (Session::get('cartData') != null) {
                foreach (Session::get('cartData') as $cart) {
                    if ($cart['type'] == 'item') {
                        array_push($items, $cart['id']);
                    }
                    if ($cart['type'] == 'combo') {
                        array_push($combo, $cart['combo_id']);
                    }
                }
                $data['package_id'] = count($combo) == 0 ? null : implode(",", $combo);
            }
            if (Session::get('GrocaryShop') != null) {
                foreach (Session::get('GrocarycartData') as $cart) {
                    array_push($items, $cart['id']);
                }
            }
            $data['items'] = count($items) == 0 ? null : implode(",", $items);
            if ($data['payment_type'] == 'LOCAL') {
                $data['payment_status'] = 0;
            }
            if (Session::get('GrocarycartData') != null)
            {
                $order = GroceryOrder::create($data);
                foreach (Session::get('GrocarycartData') as $cart) {
                    $order_child = array();
                    $order_child['order_id'] = $order->id;
                    $order_child['item_id'] = $cart['id'];
                    $order_child['price'] = $cart['price'];
                    $order_child['quantity'] = $cart['qty'];
                    GroceryOrderChild::create($order_child);
                }
            }
            if (Session::get('cartData') != null) {
                $order = Order::create($data);
                foreach (Session::get('cartData') as $cart) {
                    $order_child = array();
                    if ($cart['type'] == 'item') {
                        $order_child['order_id'] = $order->id;
                        $order_child['item'] = $cart['id'];
                        $order_child['price'] = $cart['price'];
                        $order_child['quantity'] = $cart['qty'];
                        OrderChild::create($order_child);
                    }
                    if ($cart['type'] == 'combo') {
                        $order_child['order_id'] = $order->id;
                        $order_child['package_id'] = $cart['combo_id'];
                        $order_child['price'] = $cart['price'];
                        $order_child['quantity'] = $cart['qty'];
                        OrderChild::create($order_child);
                    }
                }
            }

            $web_notification = Setting::where('web_notification', 1)->get()->first();
            $notification = NotificationTemplate::where('title', 'Create Order')->get()->first();
            $notification = $notification->message_content;
            $shop_name = CompanySetting::find(1)->name;
            if (Session::get('shop') != null) {
                $shop = Session::get('shop')['name'];
            }
            if (Session::get('GrocaryShop') != null) {
                $shop_id = Session::get('GrocaryShop');
                $grocery_shop = GroceryShop::find($shop_id);
                $shop = $grocery_shop->name;
            }
            $detail['name'] = auth()->user()->name;
            $detail['shop'] = $shop;
            $detail['shop_name'] = $shop_name;
            $data = ["{{name}}", "{{shop}}", "{{shop_name}}"];
            $message1 = str_replace($data, $detail, $notification);
            $url = url('/user_details');
            if($web_notification != null)
            {
                if ($web_notification->web_notification == 1) {
                    $user_notification = User::where('id', auth()->user()->id)->get()->first();
                    if ($user_notification->enable_notification == 1) {
                        if (auth()->user()->device_token != null) {
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
            $item['order_id'] = $order->id;
            $item['title'] = 'create order';
            $item['message'] = $message1;
            if (Session::get('shop') != null) {
                $item['notification_type'] = 'Food';
            }
            if (Session::get('GrocaryShop') != null) {
                $item['notification_type'] = 'Grocery';
            }
            Notification::create($item);

            if ($order->payment_type == 'FLUTTERWAVE')
            {
                if (Session::get('shop') != null)
                {
                    $type = 'food';
                }
                if (Session::get('GrocaryShop') != null)
                {
                    $type = 'grocery';
                }
                session()->forget('cartData');
                session()->forget('shop');
                session()->forget('GrocaryShop');
                session()->forget('GrocarycartData');
                session()->forget('coupenCode');
                return ['data' => $order, 'url' => url('FlutterWavepayment/' . $order->id.'/'.$type) ,'success' => true];
            }
            else
            {
                session()->forget('cartData');
                session()->forget('shop');
                session()->forget('GrocaryShop');
                session()->forget('GrocarycartData');
                session()->forget('coupenCode');
                return ['data' => $order, 'success' => true];
            }
        }
        else
        {
            return response(['success' => false]);
        }
    }

    public function promo_code()
    {
        $data = DB::table('coupon')->select()->get();
        return view("frontend.promo_code", compact('data'));
    }

    public function filter(Request $request)
    {
        $shops = Shop::all();
        if ($request->check == "top rated") {
            $shops = Shop::all();
            $newShops = $shops->sortByDesc('rate');
            $test = $newShops->values()->all();
            $returnString = '';
            foreach ($test as $s) {
                $returnString .= '<div class="col-md-4 col-sm-6 col-lg-3 pb-4">';
                $returnString .= '<a href="' . url('restaurants_information/' . $s->id) . '">';
                $returnString .= '<img src="' . url('images/upload/' . $s->image) . '" width="200" height="200" alt="" class="rounded-lg mb-4">';
                $returnString .= '</a>';
                $returnString .= '<div class="offerproduct text-left">';
                $returnString .= '<div class="font-weight-bold">' . $s->name;
                if ($s->veg == 1) {
                    $returnString .= '&nbsp;<img src="' . url('image/icon/veg.png') . '" alt=""><br>';
                } else {
                    $returnString .= '&nbsp;<img src="' . url('image/icon/non-veg.png') . '" alt=""><br></div>';
                }
                $returnString .= ' <div class="productcontent font">';
                $returnString .= '<div class="offer_item">'.$s->itemNames.'</div>';
                $returnString .= '<img src="'. url("image/icon/Icon ionic-ios-star.png") .'" alt="">
                '. $s->rate .'
                <img src="'. url('image/icon/Ellipse 17.png') .'" class="ml-1 mr-1"
                    alt="">'. $s->delivery_time .'Min.<br>';
                if ($s->featured == 1) {
                    $returnString .= '<span class="featured_food text-white red">Trending</span>&nbsp;';
                }
                if ($s->exclusive == 1) {
                    $returnString .= '<span class="exclusive_food text-white green">Exclusive</span>';
                }
                $returnString .= '</div>';
                $returnString .= '</div></div></div>';
            }
            return ['data' => $returnString, 'success' => true];
        }
        if ($request->check == "open now") {
            $shops = Shop::all();
            foreach ($shops as $shop)
            {
                $startDate = $shop->open_time;
                $endDate = $shop->close_time;
                $check = \Carbon\Carbon::now()->setTimezone('Asia/Kolkata')->between($startDate, $endDate);
                if ($check == true) {
                    $returnString = '';
                    foreach ($shops as $s) {
                        $returnString .= '<div class="col-md-4 col-sm-6 col-lg-3 pb-4">';
                        $returnString .= '<a href="' . url('restaurants_information/' . $s->id) . '">';
                        $returnString .= '<img src="' . url('images/upload/' . $s->image) . '" width="200" height="200" alt="" class="rounded-lg mb-4">';
                        $returnString .= '</a>';
                        $returnString .= '<div class="offerproduct text-left">';
                        $returnString .= '<div class="font-weight-bold">' . $s->name;
                        if ($s->veg == 1) {
                            $returnString .= '&nbsp;<img src="' . url('image/icon/veg.png') . '" alt=""><br>';
                        } else {
                            $returnString .= '&nbsp;<img src="' . url('image/icon/non-veg.png') . '" alt=""><br></div>';
                        }
                        $returnString .= ' <div class="productcontent font">';
                        $returnString .= '<div class="offer_item">'.$s->itemNames.'</div>';
                        $returnString .= '<img src="'. url("image/icon/Icon ionic-ios-star.png") .'" alt="">
                        '. $s->rate .'
                        <img src="'. url('image/icon/Ellipse 17.png') .'" class="ml-1 mr-1"
                            alt="">'. $s->delivery_time .'Min.<br>';
                        if ($s->featured == 1) {
                            $returnString .= '<span class="featured_food text-white red">Trending</span>&nbsp;';
                        }
                        if ($s->exclusive == 1) {
                            $returnString .= '<span class="exclusive_food text-white green">Exclusive</span>';
                        }
                        $returnString .= '</div>';
                        $returnString .= '</div></div></div>';
                    }
                    return ['data' => $returnString, 'success' => true];
                }
            }
        }
        if ($request->check == "cost low") {
            $shop = Shop::orderBy('avarage_plate_price', 'ASC')->get(); //query nu restult ave e shop na varible ma lai lejo niche nu badhu mnum rese ha
            $returnString = '';
            foreach ($shop as $s) {
                $returnString .= '<div class="col-md-4 col-sm-6 col-lg-3 pb-4">';
                $returnString .= '<a href="' . url('restaurants_information/' . $s->id) . '">';
                $returnString .= '<img src="' . url('images/upload/' . $s->image) . '" width="200" height="200" alt="" class="rounded-lg mb-4">';
                $returnString .= '</a>';
                $returnString .= '<div class="offerproduct text-left">';
                $returnString .= '<div class="font-weight-bold">' . $s->name;
                if ($s->veg == 1) {
                    $returnString .= '&nbsp;<img src="' . url('image/icon/veg.png') . '" alt=""><br>';
                } else {
                    $returnString .= '&nbsp;<img src="' . url('image/icon/non-veg.png') . '" alt=""><br></div>';
                }
                $returnString .= ' <div class="productcontent font">';
                $returnString .= '<div class="offer_item">'.$s->itemNames.'</div>';
                $returnString .= '<img src="'. url("image/icon/Icon ionic-ios-star.png") .'" alt="">
                '. $s->rate .'
                <img src="'. url('image/icon/Ellipse 17.png') .'" class="ml-1 mr-1"
                    alt="">'. $s->delivery_time .'Min.<br>';
                if ($s->featured == 1) {
                    $returnString .= '<span class="featured_food text-white red">Trending</span>&nbsp;';
                }
                if ($s->exclusive == 1) {
                    $returnString .= '<span class="exclusive_food text-white green">Exclusive</span>';
                }
                $returnString .= '</div>';
                $returnString .= '</div></div></div>';
            }
            return response(['success' => true, 'data' => $returnString]);
        }
        if ($request->check == "exclusive") {
            $shop = Shop::where('exclusive', 1)->get();
            $returnString = '';
            foreach ($shop as $s) {
                $returnString .= '<div class="col-md-4 col-sm-6 col-lg-3 pb-4">';
                $returnString .= '<a href="' . url('restaurants_information/' . $s->id) . '">';
                $returnString .= '<img src="' . url('images/upload/' . $s->image) . '" width="200" height="200" alt="" class="rounded-lg mb-4">';
                $returnString .= '</a>';
                $returnString .= '<div class="offerproduct text-left">';
                $returnString .= '<div class="font-weight-bold">' . $s->name;
                if ($s->veg == 1) {
                    $returnString .= '&nbsp;<img src="' . url('image/icon/veg.png') . '" alt=""><br>';
                } else {
                    $returnString .= '&nbsp;<img src="' . url('image/icon/non-veg.png') . '" alt=""><br></div>';
                }
                $returnString .= ' <div class="productcontent font">';
                $returnString .= '<div class="offer_item">'.$s->itemNames.'</div>';
                $returnString .= '<img src="'. url("image/icon/Icon ionic-ios-star.png") .'" alt="">
                '. $s->rate .'
                <img src="'. url('image/icon/Ellipse 17.png') .'" class="ml-1 mr-1"
                    alt="">'. $s->delivery_time .'Min.<br>';
                if ($s->featured == 1) {
                    $returnString .= '<span class="featured_food text-white red">Trending</span>&nbsp;';
                }
                if ($s->exclusive == 1) {
                    $returnString .= '<span class="exclusive_food text-white green">Exclusive</span>';
                }
                $returnString .= '</div>';
                $returnString .= '</div></div></div>';
            }
            return response(['success' => true, 'data' => $returnString]);
        }

        if ($request->check == "cost high") {
            $shop = Shop::orderBy('avarage_plate_price', 'DESC')->get();
            $returnString = '';
            foreach ($shop as $s) {
                $returnString .= '<div class="col-md-4 col-sm-6 col-lg-3 pb-4">';
                $returnString .= '<a href="' . url('restaurants_information/' . $s->id) . '">';
                $returnString .= '<img src="' . url('images/upload/' . $s->image) . '" width="200" height="200" alt="" class="rounded-lg mb-4">';
                $returnString .= '</a>';
                $returnString .= '<div class="offerproduct text-left">';
                $returnString .= '<div class="font-weight-bold">' . $s->name;
                if ($s->veg == 1) {
                    $returnString .= '&nbsp;<img src="' . url('image/icon/veg.png') . '" alt=""><br>';
                } else {
                    $returnString .= '&nbsp;<img src="' . url('image/icon/non-veg.png') . '" alt=""><br></div>';
                }
                $returnString .= ' <div class="productcontent font">';
                $returnString .= '<div class="offer_item">'.$s->itemNames.'</div>';
                $returnString .= '<img src="'. url("image/icon/Icon ionic-ios-star.png") .'" alt="">
                '. $s->rate .'
                <img src="'. url('image/icon/Ellipse 17.png') .'" class="ml-1 mr-1"
                    alt="">'. $s->delivery_time .'Min.<br>';
                if ($s->featured == 1) {
                    $returnString .= '<span class="featured_food text-white red">Trending</span>&nbsp;';
                }
                if ($s->exclusive == 1) {
                    $returnString .= '<span class="exclusive_food text-white green">Exclusive</span>';
                }
                $returnString .= '</div>';
                $returnString .= '</div></div></div>';
            }
            return response(['success' => true, 'data' => $returnString]);
        }
    }

    public function itemfilter(Request $request)
    {
        $currency_code = Setting::find(1)->currency;
        $currency = Currency::where('code', $currency_code)->first()->symbol;
        if ($request->type == 'veg') {
            $veg = Item::where([['shop_id', $request->shop_id], ['isVeg', 1]])->get();
            if (count($veg) > 0) {
                $vegData = '';
                foreach ($veg as $vegItem)
                {
                    $vegData .= '<div class="col-md-6 col-sm-12 col-12 pt-4">';
                    $vegData .= '<div class="card rounded-lg shadow">
                    <div class="card-body" id="cardBody' . $vegItem->id . '">
                    <div class="row">
                    <div class="col-md-3">';
                    $vegData .= '<img src="' . url('images/upload/' . $vegItem->image) . '" width="116" height="116" alt=""></div>';
                    $vegData .= '<div class="col-md-6">
                    <div class="text-left rest_item_name">' . $vegItem->name . '</div><div class="text-left">' . $vegItem->categoryName . '</div>';
                    $vegData .= '</div><div class="col-md-3"><div class="sessionUpdate' . $vegItem->id . '">';
                    if (Session::get('cartData') == null) {
                        $vegData .= '<span class="text-green rest_price">' . $currency . ' ' . $vegItem->price . '</span>';
                        $vegData .= '<input type="button" id="addcart' . $vegItem->id . '" value="Add + "
                        onclick="addtocart(' . $vegItem->id . ',' . $vegItem->price . ',' . "`item`" . ')" class="btn btn-outline-info mt-5 t1 float-right">';
                    } else {
                        if (in_array($vegItem->id, array_column(Session::get('cartData'), 'id'))) {
                            foreach (Session::get('cartData') as $cartitem) {
                                if ($cartitem['id'] == $vegItem->id) {
                                    if ($cartitem['type'] == 'item') {
                                        $vegData .= '<div style="margin-bottom: 7px;">';
                                        $vegData .= '<div class="cartItem">';
                                        $original_price_item = Item::find($cartitem['id'])->price;
                                        $vegData .= '<input type="hidden" id="' . "original_price" . $cartitem['id'] . '" value="' . $original_price_item . '">';
                                        $vegData .= '<span class="text-green rest_price">';
                                        $vegData .= $currency . '<input type="text" class="price text-green"
                                        id="' . "price" . $cartitem['id'] . '" value="' . $cartitem['price'] . '" readonly>';
                                        $vegData .= '</span>';
                                        $vegData .= '<span class="qty float-right mt-5">';
                                        $vegData .= '<button class="' . "minus" . $cartitem['id'] . '" onclick="update_cart(' . $cartitem['id'] . ',' . "`item`" . ',' . "`minus`" . ')">-</button>';
                                        $vegData .= '<input type="text" value="' . $cartitem['qty'] . '" id="' . 'qty' . $cartitem['id'] . '" name="qty" readonly disabled>';
                                        $vegData .= '<button onclick="update_cart(' . $cartitem['id'] . ',' . "`item`" . ',' . "`plus`" . ')">+</button>';
                                        $vegData .= '</span></div></div>';
                                    }
                                }
                            }
                        } else {
                            $vegData .= '<span class="text-green rest_price">' . $currency . ' ' . $vegItem->price . '</span>';
                            $vegData .= '<input type="button" id="addcart' . $vegItem->id . '" value="Add + "
                            onclick="addtocart(' . $vegItem->id . ',' . $vegItem->price . ',' . "`item`" . ')" class="btn btn-outline-info mt-5 t1 float-right">';
                        }
                    }
                    $vegData .= '</div></div></div></div></div></div>';
                }
                return ['data' => $vegData, 'success' => true];
            } else {
                $vegData = 'veg item not available';
                return ['data' => $vegData, 'success' => false];
            }
        }
        if ($request->type == 'non-veg') {
            $non_veg = Item::where([['shop_id', $request->shop_id], ['isVeg', 0]])->get();
            if (count($non_veg) > 0) {
                $vegData = '';
                foreach ($non_veg as $vegItem) {
                    $vegData .= '<div class="col-md-6 col-sm-12 col-12 pt-4">';
                    $vegData .= '<div class="card rounded-lg shadow">
                    <div class="card-body" id="cardBody' . $vegItem->id . '">
                    <div class="row">
                    <div class="col-md-3">';
                    $vegData .= '<img src="' . url('images/upload/' . $vegItem->image) . '" width="116" height="116" alt=""></div>';
                    $vegData .= '<div class="col-md-6">
                    <div class="text-left rest_item_name">' . $vegItem->name . '</div><div class="text-left">' . $vegItem->categoryName . '</div>';
                    $vegData .= '</div><div class="col-md-3"><div class="sessionUpdate' . $vegItem->id . '">';
                    if (Session::get('cartData') == null) {
                        $vegData .= '<span class="text-green rest_price">' . $currency . ' ' . $vegItem->price . '</span>';
                        $vegData .= '<input type="button" id="addcart' . $vegItem->id . '" value="Add + "
                        onclick="addtocart(' . $vegItem->id . ',' . $vegItem->price . ',' . "`item`" . ')" class="btn btn-outline-info mt-5 t1 float-right">';
                    } else {
                        if (in_array($vegItem->id, array_column(Session::get('cartData'), 'id'))) {
                            foreach (Session::get('cartData') as $cartitem) {
                                if ($cartitem['id'] == $vegItem->id) {
                                    if ($cartitem['type'] == 'item') {
                                        $vegData .= '<div style="margin-bottom: 7px;">';
                                        $vegData .= '<div class="cartItem">';
                                        $original_price_item = Item::find($cartitem['id'])->price;
                                        $vegData .= '<input type="hidden" id="' . "original_price" . $cartitem['id'] . '" value="' . $original_price_item . '">';
                                        $vegData .= '<span class="text-green rest_price">';
                                        $vegData .= $currency . '<input type="text" class="price text-green"
                                        id="' . "price" . $cartitem['id'] . '" value="' . $cartitem['price'] . '" readonly>';
                                        $vegData .= '</span>';
                                        $vegData .= '<span class="qty float-right mt-5">';
                                        $vegData .= '<button class="' . "minus" . $cartitem['id'] . '" onclick="update_cart(' . $cartitem['id'] . ',' . "`item`" . ',' . "`minus`" . ')">-</button>';
                                        $vegData .= '<input type="text" value="' . $cartitem['qty'] . '" id="' . 'qty' . $cartitem['id'] . '" name="qty" readonly disabled>';
                                        $vegData .= '<button onclick="update_cart(' . $cartitem['id'] . ',' . "`item`" . ',' . "`plus`" . ')">+</button>';
                                        $vegData .= '</span></div></div>';
                                    }
                                }
                            }
                        } else {
                            $vegData .= '<span class="text-green rest_price">' . $currency . ' ' . $vegItem->price . '</span>';
                            $vegData .= '<input type="button" id="addcart' . $vegItem->id . '" value="Add + "
                            onclick="addtocart(' . $vegItem->id . ',' . $vegItem->price . ',' . "`item`" . ')" class="btn btn-outline-info mt-5 t1 float-right">';
                        }
                    }
                    $vegData .= '</div></div></div></div></div></div>';
                }
                return ['data' => $vegData, 'success' => true];
            } else {
                $vegData = 'Non veg item not available';
                return ['data' => $vegData, 'success' => false];
            }
        }
        if ($request->type == 'all') {
            $all = Item::where('shop_id', $request->shop_id)->get();
            if (count($all) > 0) {
                $vegData = '';
                foreach ($all as $vegItem) {
                    $vegData .= '<div class="col-md-6 col-sm-12 col-12 pt-4">';
                    $vegData .= '<div class="card rounded-lg shadow">
                    <div class="card-body" id="cardBody' . $vegItem->id . '">
                    <div class="row">
                    <div class="col-md-3">';
                    $vegData .= '<img src="' . url('images/upload/' . $vegItem->image) . '" width="116" height="116" alt=""></div>';
                    $vegData .= '<div class="col-md-6">
                    <div class="text-left rest_item_name">' . $vegItem->name . '</div><div class="text-left">' . $vegItem->categoryName . '</div>';
                    $vegData .= '</div><div class="col-md-3"><div class="sessionUpdate' . $vegItem->id . '">';
                    if (Session::get('cartData') == null) {
                        $vegData .= '<span class="text-green rest_price">' . $currency . ' ' . $vegItem->price . '</span>';
                        $vegData .= '<input type="button" id="addcart' . $vegItem->id . '" value="Add + "
                        onclick="addtocart(' . $vegItem->id . ',' . $vegItem->price . ',' . "`item`" . ')" class="btn btn-outline-info mt-5 t1 float-right">';
                    } else {
                        if (in_array($vegItem->id, array_column(Session::get('cartData'), 'id'))) {
                            foreach (Session::get('cartData') as $cartitem) {
                                if ($cartitem['id'] == $vegItem->id) {
                                    if ($cartitem['type'] == 'item') {
                                        $vegData .= '<div style="margin-bottom: 7px;">';
                                        $vegData .= '<div class="cartItem">';
                                        $original_price_item = Item::find($cartitem['id'])->price;
                                        $vegData .= '<input type="hidden" id="' . "original_price" . $cartitem['id'] . '" value="' . $original_price_item . '">';
                                        $vegData .= '<span class="text-green rest_price">';
                                        $vegData .= $currency . '<input type="text" class="price text-green"
                                        id="' . "price" . $cartitem['id'] . '" value="' . $cartitem['price'] . '" readonly>';
                                        $vegData .= '</span>';
                                        $vegData .= '<span class="qty float-right mt-5">';
                                        $vegData .= '<button class="' . "minus" . $cartitem['id'] . '" onclick="update_cart(' . $cartitem['id'] . ',' . "`item`" . ',' . "`minus`" . ')">-</button>';
                                        $vegData .= '<input type="text" value="' . $cartitem['qty'] . '" id="' . 'qty' . $cartitem['id'] . '" name="qty" readonly disabled>';
                                        $vegData .= '<button onclick="update_cart(' . $cartitem['id'] . ',' . "`item`" . ',' . "`plus`" . ')">+</button>';
                                        $vegData .= '</span></div></div>';
                                    }
                                }
                            }
                        } else {
                            $vegData .= '<span class="text-green rest_price">' . $currency . ' ' . $vegItem->price . '</span>';
                            $vegData .= '<input type="button" id="addcart' . $vegItem->id . '" value="Add + "
                            onclick="addtocart(' . $vegItem->id . ',' . $vegItem->price . ',' . "`item`" . ')" class="btn btn-outline-info mt-5 t1 float-right">';
                        }
                    }
                    $vegData .= '</div></div></div></div></div></div>';
                }
                // dd($vegData);
                return ['data' => $vegData, 'success' => true];
            } else {
                $vegData = 'veg item not available';
                return ['data' => $vegData, 'success' => false];
            }
        }
    }

    public function addBookmark(Request $request)
    {
        $id = $request->id;
        if(auth()->check())
        {
            $data = User::find(auth()->user()->id);
            if ($data->favourite == null) {
                User::find(auth()->user()->id)->update(array('favourite' => $id));
                return response(['success' => true, 'data' => 'add into favorite']);
            } else {
                $bookmark = [];
                $bookmark['favourite'] = $data->favourite;
                array_push($bookmark, $id);
                $data->favourite = implode(",", $bookmark);
                $data->save();
                return response(['success' => true, 'data' => 'add into favorite']);
            }
        }
        else
        {
            return response(['success' => false, 'data' => 'Please login first..']);
        }
    }

    public function cancel_order($id)
    {
        Order::find($id)->update(array('order_status' => 'Cancel'));
        $web_notification = Setting::where('web_notification', 1)->get()->first();
        $notification = NotificationTemplate::where('title', 'Order Status')->get()->first();
        $notification = $notification->message_content;
        $shop_name = CompanySetting::find(1)->name;
        $s = Order::find($id)->shop_id;
        $order_no = Order::find($id)->order_no;
        $status = Order::find($id)->order_status;
        $shop = Shop::find($s)->name;
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
        $item['notification_type'] = 'Food';
        Notification::create($item);
        return redirect('user_details');
    }

    public function search(Request $request)
    {
        $lat = $request->lat;
        $lng = $request->lang;
        $s = array();
        if ($request->search_meal == null && $request->address == null)
        {
            $itemString = 'Search value cannot be empty..!!';
            return ['success' => false, 'data' => $itemString];
        }
        if ($request->search_meal != null && $request->address != null)
        {
            $data = DB::table('shop')
                ->select(
                    'id',
                    'name',
                    'radius',
                    DB::raw(sprintf(
                        '(6371 * acos(cos(radians(%1$.7f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%2$.7f)) + sin(radians(%1$.7f)) * sin(radians(latitude)))) AS distance',
                        $lat,
                        $lng
                    ))
                )->orderBy('distance', 'asc')->get();
                $s = array();
                foreach ($data as $value)
                {
                    $shop = Shop::find($value->id);
                    if($shop['radius'] >= $value->distance)
                    {
                        array_push($s, $shop->id);
                    }
                }
            $shops = Shop::whereIn('id', $s)->orderBy('id', 'DESC')->get();
            if (count($shops) > 0)
            {
                $itemString = '';
                $itemString .= '<div class="container-fuild p-2 pb-5 search">
                <div class="container mb-5 couponContainer">
                <div class="row"><div class="col-md-9 col-sm-9 col-6 float-left">
                <div class="mt-5 text-left index_display_title">Searching Items..</div><hr class="hr">
                </div>
                <div class="col-md-3 col-sm-3 col-6 float-right">
                <div class="mt-5 mr-3 text-green link_view_all">Explorer all<img src="/image/arrow right.png" class="ml-3" alt="">
                </div></div></div>
                <div class="row scrollContainer listOffers">';
                foreach ($shops as $shop) {
                    $items = Item::with(['shop'])->where([['shop_id', $shop->id], ['name', 'LIKE', "%{$request->search_meal}%"]])->get();
                    if (count($items) > 0) {
                        foreach ($items as $item) {
                            $itemString .= '<div class="col-md-4 col-6 col-sm-6 col-lg-3">';
                            $itemString .= '<img src="' . url('images/upload/' . $item->image) . '" height="200" width="200" alt="" class="rounded-lg mb-4">';
                            $itemString .= '<div class="offerproduct text-left">';
                            $itemString .= '<div class="font-weight-bold">' . $item['shop']->name;
                            if ($item->isVeg == 1) {
                                $itemString .= '<img src="' . url('image/icon/veg.png') . '" alt=""><br></div>';
                            } else {
                                $itemString .= '<img src="' . url('image/icon/non-veg.png') . '" alt=""><br></div>';
                            }
                            $itemString .= '<div class="productcontent font"><div class="offer_item">';
                            $itemString .= $item->name . '</div></div>';
                        }
                        $itemString .= '</div></div>';
                    }
                }
                return ['success' => true, 'data' => $itemString];
            } else {
                $itemString = 'Nothing to display try to search again..!!';
                return ['success' => false, 'data' => $itemString];
            }
        }
        if ($request->search_meal != null) {
            $items = Item::with(['category', 'shop'])->where('name', 'LIKE', "%{$request->search_meal}%")->get();
            if (count($items) > 0) {
                $itemString = '';
                $itemString .= '<div class="container-fuild p-2 pb-5 search">
                <div class="container mb-5 couponContainer">
                <div class="row"><div class="col-md-9 col-sm-9 col-6 float-left">
                <div class="mt-5 text-left index_display_title">Searching Items..</div><hr class="hr">
                </div>
                <div class="col-md-3 col-sm-3 col-6 float-right">
                <div class="mt-5 mr-3 text-green link_view_all">Explorer all<img src="/image/arrow right.png" class="ml-3" alt="">
                </div></div></div>
                <div class="row scrollContainer listOffers">';
                foreach ($items as $item) {
                    $itemString .= '<div class="col-md-4 col-6 col-sm-6 col-lg-3">';
                    $itemString .= '<img src="' . url('images/upload/' . $item->image) . '" height="200" width="200" alt="" class="rounded-lg mb-4">';
                    $itemString .= '<div class="offerproduct text-left">';
                    $itemString .= '<div class="font-weight-bold">' . $item['shop']->name;
                    if ($item->isVeg == 1) {
                        $itemString .= '<img src="' . url('image/icon/veg.png') . '" alt=""><br></div>';
                    } else {
                        $itemString .= '<img src="' . url('image/icon/non-veg.png') . '" alt=""><br></div>';
                    }
                    $itemString .= '<div class="productcontent font"><div class="offer_item">';
                    $itemString .= $item->name . '</div></div>';
                }
                $itemString .= '</div></div>';
                $itemString .= '</div></div></div>';
                return ['success' => true, 'data' => $itemString];
            } else {
                $itemString = 'Nothing to display try to search again..!!';
                return ['success' => false, 'data' => $itemString];
            }
        }

        if ($request->address != null) {

            $data = DB::table('shop')
                ->select('id', 'name', 'radius', DB::raw(sprintf(
                    '(6371 * acos(cos(radians(%1$.7f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%2$.7f)) + sin(radians(%1$.7f)) * sin(radians(latitude)))) AS distance',
                    $lat,
                    $lng
                )))->orderBy('distance', 'asc')->get();
            if (count($data) > 0) {
                foreach ($data as $value) {
                    $shop = Shop::find($value->id);
                    if ($shop->radius >= $value->distance) {
                        array_push($s, $shop->id);
                    }
                }
            }
            $shops = Shop::whereIn('id', $s)->orderBy('id', 'DESC')->get();
            if (count($shops) > 0) {
                $itemString = '';
                $itemString .= '<div class="container-fuild p-2 pb-5 search">
                <div class="container mb-5 couponContainer">
                <div class="row"><div class="col-md-9 col-sm-9 col-6 float-left">
                <div class="mt-5 text-left index_display_title">Searching Items..</div><hr class="hr">
                </div>
                <div class="col-md-3 col-sm-3 col-6 float-right">
                <div class="mt-5 mr-3 text-green link_view_all">Explorer all<img src="/image/arrow right.png" class="ml-3" alt="">
                </div></div></div>
                <div class="row scrollContainer listOffers">';
                foreach ($shops as $shop) {
                    $itemString .= '<div class="col-md-4 col-6 col-sm-6 col-lg-3">';
                    $itemString .= '<a href="' . url('restaurants_information/' . $shop->id) . '">';
                    $itemString .= '<img src="' . url('images/upload/' . $shop->image) . '" height="200" width="200" alt="" class="rounded-lg mb-4"></a>';
                    $itemString .= '<div class="offerproduct text-left">';
                    $itemString .= '<div class="font-weight-bold">' . $shop->name;
                    if ($shop->isVeg == 1) {
                        $itemString .= '<img src="' . url('image/icon/veg.png') . '" alt=""><br></div>';
                    } else {
                        $itemString .= '<img src="' . url('image/icon/non-veg.png') . '" alt=""><br></div>';
                    }
                    $itemString .= '<div class="productcontent font"><div class="offer_item">';
                    $itemString .= $shop->itemNames . '</div>';
                    $itemString .= '<img src="' . url('image/icon/Icon ionic-ios-star.png') . '" alt="">' . $shop->rate;
                    $itemString .= '<img src="' . url('image/icon/Ellipse 17.png') . '" class="ml-1 mr-1" alt="">' . $shop->delivery_time . 'Min.<br>';
                    if ($shop->featured == 1) {
                        $itemString .= '<span class="featured_food text-white red">Trending</span>';
                    }
                    if ($shop->exclusive == 1) {
                        $itemString .= '<span class="exclusive_food text-white green">Exclusive</span>';
                    }
                    $itemString .= '</div></div></div>';
                }
                $itemString .= '</div></div></div>';
                return ['success' => true, 'data' => $itemString];
            } else {
                $itemString = 'Nothing to display try to search again..!!';
                return ['success' => false, 'data' => $itemString];
            }
        }
    }

    public function removeSingleItem(Request $request)
    {
        if (Session::get('cartData') != null) {
            $cart = Session::get('cartData');
        }
        if (Session::get('GrocarycartData') != null) {
            $cart = Session::get('GrocarycartData');
        }
        foreach ($cart as $index => $product) {
            if (isset($product['id'])) {
                if ($product['id'] == $request->id) {
                    unset($cart[$index]);
                }
            }
            if (isset($product['combo_id'])) {
                if ($product['combo_id'] == $request->id) {
                    unset($cart[$index]);
                }
            }
        }
        if (Session::get('cartData') != null) {
            session(['cartData' => $cart]);
        }
        if (Session::get('GrocarycartData') != null) {
            session(['GrocarycartData' => $cart]);
        }
        return response(['success' => true, 'data' => 'Item Deleted successfully..!!']);
    }

    public function FlutterWavepayment($order_id,$type)
    {
        if ($type == 'food')
        {
            $order = Order::find($order_id);
        }
        if($type == 'grocery')
        {
            $order = GroceryOrder::find($order_id);
        }
        return view('frontend/flutterPayment', compact('order'));
    }

    public function transction_verify(Request $request, $order)
    {
        $order = Order::find($order);
        $id = $request->input('transaction_id');
        if ($request->input('status') == 'successful')
        {
            $order->payment_token = $id;
            $order->payment_status = 1;
            $order->save();
            return redirect('user_details');
        }
        else
        {
            return redirect('order_confirmation');
        }
    }

    public function paystackPayment($order_id)
    {
        $order = Order::find($order_id);
        return view('frontend.PaystackPayment');
    }

    public function paytmPayment()
    {
        $payment = PaytmWallet::with('receive');
        $payment->prepare([
          'order' => '#121212',
          'user' => 41,
          'mobile_number' => 455677890234,
          'email' => 'user-foodlands@saasmonks.in',
          'amount' => 200,
          'callback_url' => url('payTm')
        ]);
        return $payment->receive();
        // return response(['data' => $payment->receive() , 'success' => true]);
    }

    public function payTm()
    {
        $transaction = PaytmWallet::with('receive');

        $response = $transaction->response();

        $order_id = $transaction->getOrderId(); // return a order id

        $transaction->getTransactionId(); // return a transaction id
        dd($transction);
    }

    public function redirectToGateway()
    {
        // try
        // {
            return Paystack::getAuthorizationUrl()->redirectNow();
        // }
        // catch(\Exception $e)
        // {
            // return ::back()->withMessage(['msg'=>'The paystack token has expired. Please refresh the page and try again.', 'type'=>'error']);
            return redirect()->back()->withMessage(['msg'=>'The paystack token has expired. Please refresh the page and try again.', 'type'=>'error']);
        // }
    }
}
