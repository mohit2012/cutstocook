<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <?php $logo = \App\CompanySetting::find(1)->logo; ?>

        @if(Auth::check())
        <a class="navbar-brand pt-0" href="{{url('owner/home')}}">
        @elseif(Auth::guard('mainAdmin')->check())
        <a class="navbar-brand pt-0" href="{{url('mainAdmin/home')}}">
        @endif
            <img src="{{ url('images/upload/'.$logo)}}" class="navbar-brand-img" alt="...">
        </a>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                        <img alt="Image placeholder" src="{{ url('admin/images/1.jpg') }}">
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                    </div>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('My profile') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-settings-gear-65"></i>
                        <span>{{ __('Settings') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-calendar-grid-58"></i>
                        <span>{{ __('Activity') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-support-16"></i>
                        <span>{{ __('Support') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                        style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="#">
                            <img src="{{ url('images/upload/'.$logo) }}">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Form -->
            <form class="mt-4 mb-3 d-md-none">
                <div class="input-group input-group-rounded input-group-merge">
                    <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="{{ __('Search') }}" aria-label="Search">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-search"></span>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Navigation -->
            {{-- {{dd(request()->is('owner/home'))}} --}}
            <ul class="navbar-nav">
                @if(Auth::check())
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('owner/home')  ? 'active' : ''}}" href="{{url('owner/home')}}">
                        <i class="ni ni-chart-pie-35 text-primary"></i> {{ __('Dashboard') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('owner/ViewOrder')  ? 'active' : ''}}" href="{{url('owner/ViewOrder')}}">
                        <i class="ni ni-calendar-grid-58 text-danger"></i> {{ __('Orders') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('owner/GroceryOrder')  ? 'active' : ''}}" href="{{url('owner/GroceryOrder')}}">
                        <i class="ni ni-calendar-grid-58 text-primary"></i> {{ __('Grocery Orders') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('owner/Category') || request()->is('owner/Shop') || request()->is('owner/Item') || request()->is('owner/Coupon') || request()->is('owner/Gallery')? 'active' : ''}}"  href="#food-expand" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="food-expand">
                        <i class="fas fa-utensils" style="color: #5e72e4;"></i>
                        <span class="nav-link-text" >{{ __('Food') }}</span>
                    </a>                    
                    <div class="collapse {{ request()->is('owner/Category') || request()->is('owner/Shop') || request()->is('owner/Item') || request()->is('owner/Coupon') || request()->is('owner/Gallery') ? 'show' : ''}}" id="food-expand">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('owner/Category')  ? 'active-tab' : ''}}" href="{{url('owner/Category')}}">
                                    <i class="ni ni-app text-pink" ></i> {{ __('Categories') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('owner/Shop')  ? 'active-tab' : ''}}" href="{{url('owner/Shop')}}">
                                    <i class="fas fa-store text-success"></i> {{ __('Shop') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('owner/Item')  ? 'active-tab' : ''}}" href="{{url('owner/Item')}}">
                                    <i class="fas fa-utensils" style="color:#8abf4d;"></i> {{ __('Items') }}
                                </a>
                            </li>
                            @if(View::exists('admin.coupon.viewCoupon'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('owner/Coupon')  ? 'active-tab' : ''}}" href="{{url('owner/Coupon')}}">
                                    <i class="fas fa-tags text-orange"></i> {{ __('Food Coupon') }}
                                </a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('owner/Gallery')  ? 'active-tab' : ''}}" href="{{url('owner/Gallery')}}">
                                    <i class="ni ni-image text-orange" style="color:#8abf4d;"></i> {{ __('Gallery') }}
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>

                
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('owner/GrocerySubCategory') || request()->is('owner/GroceryShop') ||request()->is('owner/GroceryItem') || request()->is('owner/GroceryCoupon') ? 'active' : ''}}" href="#grocery-expand" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="grocery-expand">
                        <i class="fas fa-apple-alt" style="color: #8abf4d;"></i>
                        <span class="nav-link-text" >{{ __('Grocery') }}</span>
                    </a>                    
                    <div class="collapse {{ request()->is('owner/GrocerySubCategory') || request()->is('owner/GroceryShop') ||request()->is('owner/GroceryItem') || request()->is('owner/GroceryCoupon')  ? 'show' : ''}}" id="grocery-expand">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('owner/GrocerySubCategory')  ? 'active-tab' : ''}}" href="{{url('owner/GrocerySubCategory')}}">
                                    <i class="fas fa-list-ul text-primary"></i> {{ __('Grocery SubCategory') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('owner/GroceryShop')  ? 'active-tab' : ''}}" href="{{url('owner/GroceryShop')}}">
                                    <i class="fas fa-store-alt text-orange" style="color:#8abf4d;"></i> {{ __('Grocery Shop') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('owner/GroceryItem')  ? 'active-tab' : ''}}" href="{{url('owner/GroceryItem')}}">
                                    <i class="fas fa-utensils"></i> {{ __('Grocery Item') }}
                                </a>
                            </li>
                            @if(View::exists('admin.coupon.viewCoupon'))
                            <li class="nav-item ">
                                <a class="nav-link {{ request()->is('owner/GroceryCoupon')  ? 'active-tab' : ''}}" href="{{url('owner/GroceryCoupon')}}">
                                    <i class="fas fa-tags text-orange"></i> {{ __('Grocery Coupon') }}
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
    
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('owner/Package')  ? 'active' : ''}}" href="{{url('owner/Package')}}">
                        <i class="fas fa-gift text-danger"></i> {{ __('Special Combo & Offers') }}
                    </a>
                </li>
                
                             
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('owner/notifications')  ? 'active' : ''}}" href="{{url('owner/notifications')}}">
                        <i class="fas fa-bell text-success" ></i> {{ __('Notification') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('owner/ownerRevenueReport')  || request()->is('owner/groceryRevenueReport') ? 'active' : ''}}" href="#report-expand" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="report-expand">
                        <i class="fas fa-chart-bar" style="color: #5e72e4;"></i>
                        <span class="nav-link-text" >{{ __('Reports') }}</span>
                    </a>
                    <div class="collapse {{ request()->is('owner/ownerRevenueReport') || request()->is('owner/groceryRevenueReport') ? 'show' : ''}}" id="report-expand">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('owner/ownerRevenueReport')}}">
                                    <i class="ni ni-sound-wave" style="color:#8abf4d;"></i> {{ __('Revenue Report') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('owner/groceryRevenueReport')}}">
                                    <i class="ni ni-chart-pie-35" style="color:#8abf4d;"></i> {{ __('Grocery Revenue Report') }}
                                </a>
                            </li>
                        </ul>
                    </div>               
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('owner/OwnerSetting')  ? 'active' : ''}}" href="{{url('owner/OwnerSetting')}}">
                        <i class="ni ni-settings-gear-65 text-info"></i> {{ __('Setting') }}
                    </a>
                </li>


                @elseif(Auth::guard('mainAdmin')->check())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('mainAdmin/home')  ? 'active' : ''}}" href="{{url('mainAdmin/home')}}">
                            <i class="ni ni-chart-pie-35 text-primary"></i> {{ __('Dashboard') }}
                        </a>
                    </li>
                
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('mainAdmin/Location')  ? 'active' : ''}}" href="{{url('mainAdmin/Location')}}">
                            <i class="ni ni-pin-3 "  style="color: #ff9200;"></i> {{ __('Locations') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('mainAdmin/AdminCategory')  ? 'active' : ''}}" href="{{url('mainAdmin/AdminCategory')}}">
                            <i class="ni ni-app "  style="color: #f3a4b5;"></i> {{ __('Categories') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('mainAdmin/AdminShop')  ? 'active' : ''}}" href="{{url('mainAdmin/AdminShop')}}">
                            <i class="fas fa-store text-primary"  ></i> {{ __('Shop') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('mainAdmin/AdminItem')  ? 'active' : ''}}" href="{{url('mainAdmin/AdminItem')}}">
                            <i class="fas fa-utensils"  style="color: #81bd3b;"></i> {{ __('Items') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('mainAdmin/GroceryCategory')  ? 'active' : ''}}" href="{{url('mainAdmin/GroceryCategory')}}">
                            <i class="ni ni-app "  style="color: #f3a4b5;"></i> {{ __('Grocery Category') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('mainAdmin/viewGroceryShop')  ? 'active' : ''}}" href="{{url('mainAdmin/viewGroceryShop')}}">
                            <i class="fas fa-store text-primary"  ></i> {{ __('Grocery Shop') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('mainAdmin/viewGroceryItem')  ? 'active' : ''}}" href="{{url('mainAdmin/viewGroceryItem')}}">
                            <i class="fas fa-utensils"  style="color: #81bd3b;"></i> {{ __('Grocery Item') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('mainAdmin/Banner')  ? 'active' : ''}}" href="{{url('mainAdmin/Banner')}}">
                            <i class="ni ni-image"  style="color: #cb6c78;"></i> {{ __('Image Slider') }}
                        </a>
                    </li>

                    <li class="nav-item">
                            <a class="nav-link {{ request()->is('mainAdmin/Customer') || request()->is('mainAdmin/shopOwners') || request()->is('mainAdmin/deliveryGuys') ? 'active' : ''}}" href="#navbar-examples" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-examples">
                                <i class="ni ni-circle-08"  style="color: #1592e2;"></i>
                                <span class="nav-link-text" >{{ __('Manage Users') }}</span>
                            </a>

                            <div class="collapse {{ request()->is('mainAdmin/Customer') || request()->is('mainAdmin/shopOwners') || request()->is('mainAdmin/deliveryGuys') ? 'show' : ''}}" id="navbar-examples">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('mainAdmin/Customer')  ? 'active-tab' : ''}}" href="{{url('mainAdmin/Customer')}}">
                                        <i class="fas fa-users" style="color:#8abf4d;"></i> {{ __('Users') }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('mainAdmin/shopOwners')  ? 'active-tab' : ''}} " href="{{url('mainAdmin/shopOwners')}}">
                                            <i class="fas fa-user-tie" style="color:#8abf4d;"></i>   {{ __('Shop Owners') }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('mainAdmin/deliveryGuys')  ? 'active-tab' : ''}}" href="{{url('mainAdmin/deliveryGuys')}}">
                                        <i class="fas fa-truck-moving" style="color:#8abf4d;"></i> {{ __('Delivery Guys') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('mainAdmin/customerReport') || request()->is('mainAdmin/revenueReport') ? 'active' : ''}}" href="#report-expand" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="report-expand">
                            <i class="fas fa-chart-bar" style="color: #5e72e4;"></i>
                            <span class="nav-link-text" >{{ __('Reports') }}</span>
                        </a>

                        <div class="collapse {{ request()->is('mainAdmin/customerReport') || request()->is('mainAdmin/revenueReport') ? 'show' : ''}}" id="report-expand">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                <a class="nav-link {{ request()->is('mainAdmin/customerReport')  ? 'active-tab' : ''}}" href="{{url('mainAdmin/customerReport')}}">
                                        <i class="ni ni-paper-diploma" style="color:#ff9200;"></i> {{ __('Customer Report') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('mainAdmin/revenueReport')  ? 'active-tab' : ''}}" href="{{url('mainAdmin/revenueReport')}}">
                                        <i class="ni ni-sound-wave" style="color:#8abf4d;"></i> {{ __('Revenue Report') }}
                                    </a>
                                </li>                               
                               
                                {{-- <li class="nav-item">
                                    <a class="nav-link" href="{{url('mainAdmin/customer-loyalty-report')}}">
                                        <i class="fas fa-crown" style="color:#cb6c78;"></i> {{ __('Customer Loyalty Point Report') }}
                                    </a>
                                </li> --}}

                            </ul>
                        </div>
                    </li>
                    @if(View::exists('mainAdmin.notification.viewNotification'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('mainAdmin/NotificationTemplate')  ? 'active' : ''}}" href="{{url('mainAdmin/NotificationTemplate')}}">
                            <i class="fas fa-bell"  style="color: #f53d55;"></i> {{ __('Notification') }}
                        </a>
                    </li>
                    @endif
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{url('Role')}}">
                            <i class="fas fa-user-secret" style="color: #333;"></i> {{ __('Roles & Permission') }}
                        </a>
                    </li> --}}
                    {{-- <li class="nav-item">
                        <a class="nav-link {{ request()->is('mainAdmin/module')  ? 'active' : ''}}" href="{{url('mainAdmin/module')}}">
                            <i class="fas fa-layer-group" style="color:#f5660b;"></i> {{ __('Modules') }}
                        </a>
                    </li> --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('mainAdmin/adminSetting')  ? 'active' : ''}}" href="{{url('mainAdmin/adminSetting')}}">
                            <i class="ni ni-settings-gear-65 text-info"></i> {{ __('Setting') }}
                        </a>
                    </li>
                @endif

            </ul>          

        </div>
    </div>
</nav>
