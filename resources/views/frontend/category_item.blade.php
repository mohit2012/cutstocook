@extends('frontend.layouts.app')

@section('title','Grocery Category')
@section('content')
<div class="container">
    <nav class="navbar grocery_navbar">
        @foreach ($grocery_categories as $grocery_category)
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link sub_cat_display t1 {{ $grocery_categories_name->id == $grocery_category->id ? 'active_link' : ''}}
                    {{ $grocery_category->id }}" onclick="display_category({{$grocery_category->id}}, this)">
                    {{ $grocery_category->name }}</a>
            </li>
        </ul>
        @endforeach
    </nav>
</div>
<div class="container-fuild grocery_category_bg"  style="background-image: url({{url('frontend/image/icon/Rectangle_1118.png')}});"  >
    <div class="container couponContainers pt-5">
        <div class="row">
            <div class="col">
                <hr class="hr_white">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="category_name font-weight-bolder text-center text-white">{{$grocery_categories_name->name}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <hr class="hr_white">
            </div>
        </div>
    </div>
</div>

<div class="container-fuild bg-light">
    <div class="container pb-5 couponContainer">
        <div class="grocery_item">
            @if (count($grocery_sub_categories) != 0)
            @foreach ($grocery_sub_categories as $grocery_sub_category)
            <?php $categories_item = \App\GroceryItem::where('subcategory_id', $grocery_sub_category->id)->get();  ?>
            @if(count($categories_item)>0)
            <div class="row p-3">
                <div class="col-md-9 col-sm-9 col-9">
                    <div class="sub_category_name text-left">
                        {{$grocery_sub_category->name}}
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-3">
                    <div class="mr-3 text-green link_view_all">Explorer all                        
                        <i class="fa fa-chevron-right ml-3"></i>
                    </div>
                </div>
            </div>
            @endif
            <div class="row scrollContainer cartItem">
                @if(count($categories_item)>0)
                @foreach ($categories_item as $category_item)
               
                <div class="col-md-4 col-sm-6 col-lg-3 pb-4">
                    <div class="offerProduct grocery_content w-100 h-100 text-left rounded-lg bg-white p-3">
                        <a href="{{ url('single_grocery/'.$category_item->id) }}"><img
                                src={{url("images/upload/".$category_item->image)}} height="200" width="200" alt=""
                                class="mb-4 rounded-lg"></a><br>
                        <div class="t1">
                            <div class="font-weight-bold pb-5">
                                {{ $category_item->name }}<br>
                                {{ $category_item->weight }}gm<br>
                            </div>
                            @if (Session::get('GrocarycartData') == null)
                            <div class="row grocery_row">
                                <div class="col left_col text-left">
                                    <span class="qty">
                                        <button class="minus"
                                            onclick="add_grocery_cart({{$category_item->id}},'minus')">-</button>
                                        <input type="text" value="0" id="{{'qty' . $category_item->id}}" name="qty"
                                            readonly disabled>
                                        <button onclick="add_grocery_cart({{$category_item->id}},'plus')">+</button>
                                    </span>
                                </div>
                                <div class="col right_col text-green text-right">
                                    {{ $currency }}<input type="text" class="price text-green"
                                        id="{{'price' . $category_item['id']}}" value="{{ $category_item->sell_price }}"
                                        class="form-control" readonly>
                                </div>
                            </div>
                            @else
                            @if(in_array($category_item->id, array_column(Session::get('GrocarycartData'), 'id')))
                            @foreach (Session::get('GrocarycartData') as $cartData)
                            @if($cartData['id'] == $category_item->id)
                            <div class="row grocery_row">
                                <div class="col left_col text-left">
                                    <span class="qty">
                                        <button class="minus"
                                            onclick="add_grocery_cart({{$category_item->id}},'minus')">-</button>
                                        <input type="text" value="{{$cartData['qty']}}"
                                            id="{{'qty' . $category_item->id}}" name="qty" readonly disabled>
                                        <button onclick="add_grocery_cart({{$category_item->id}},'plus')">+</button>
                                    </span>
                                </div>
                                <div class="col right_col text-green text-right">
                                    {{ $currency }}<input type="text" class="price text-green"
                                        id="{{'price' . $category_item->id}}" value="{{ $cartData['price'] }}"
                                        class="form-control" readonly>
                                </div>
                            </div>
                            @endif
                            @endforeach
                            @else   
                            <div class="row grocery_row">
                                <div class="col left_col text-left">
                                    <span class="qty">
                                        <button class="minus"
                                            onclick="add_grocery_cart({{$category_item->id}},'minus')">-</button>
                                        <input type="text" value="0" id="{{'qty' . $category_item->id}}" name="qty"
                                            readonly disabled>
                                        <button onclick="add_grocery_cart({{$category_item->id}},'plus')">+</button>
                                    </span>
                                </div>
                                <div class="col right_col text-green text-right">
                                    {{ $currency }}<input type="text" class="price text-green"
                                        id="{{'price' . $category_item->id}}" value="{{ $category_item->sell_price }}"
                                        class="form-control" readonly>
                                </div>
                            </div>
                            @endif
                            @endif
                            <input type="hidden" value="{{ $category_item->sell_price }}"
                                id="{{'original_price' . $category_item['id']}}">
                        </div>
                    </div>
                </div>
                @endforeach
                @else              
                @endif
            </div>
            @endforeach
            @else
            <div class="sub_category_name text-left">No Item Available...</div>
            @endif
        </div>
    </div>
</div>
@endsection
