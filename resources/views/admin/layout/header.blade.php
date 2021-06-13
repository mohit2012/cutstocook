<!-- Top navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
    <div class="container-fluid">
        <!-- Brand -->
        @if(Auth::check())

        <?php $notification = \App\AdminNotification::where('owner_id',Auth::user()->id)->orderBy('id', 'DESC')->get(); ?>

        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="{{url('owner/home')}}">{{ __('Dashboard') }}</a>
        @elseif(Auth::guard('mainAdmin')->check()) 
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="{{url('mainAdmin/home')}}">{{ __('Dashboard') }}</a>
        @endif
        
        @if(Auth::check())
         <?php $image = Auth::user()->image; ?>
        @elseif(Auth::guard('mainAdmin')->check()) 
            <?php $image = Auth::guard('mainAdmin')->user()->image;  ?>
        @endif
        <?php 
            $sell_product =  \App\Setting::find(1)->sell_product;
            if($sell_product == 1){ $product = 'Grocery'; }
            if($sell_product == 2){ $product = 'Food'; }
        ?>
        <ul class="navbar-nav align-items-center d-none d-md-flex"> 
            @if($sell_product!= 0)
            <li class="nav-item dropdown show">
                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <div class="media align-items-center">                       
                        <i class="ni ni-notification-70" style="color:#ec3e37"></i>                                           
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right py-0 overflow-hidden show">
                    <a href="#" style="color:#c35866" class="dropdown-item"><span>{{$product}} Delivery is Disable by admin.</span> </a>                                               
                </div>
            </li>
            @endif
            @if(Auth::check())
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">                       
                        <i class="ni ni-bell-55"></i>                                            
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-xl dropdown-menu-arrow dropdown-menu-right py-0 overflow-hidden">                  
                    <div class="list-group list-group-flush"> 
                        @if(count($notification)>0)
                        @foreach ($notification as $item)
                        @if($loop->iteration<=3)
                          <a href="{{url('owner/viewOrder/'.$item->order_id.$item->userData->order_no)}}" class="list-group-item list-group-item-action">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                <!-- Avatar -->                                   
                                <img alt="Image placeholder" src="{{url('images/upload/'.$item->userData->image)}}" class="avatar rounded-circle">
                                </div>
                                <div class="col ml--2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                        <h4 class="mb-0 text-sm">{{$item->userData->name}}</h4>
                                        </div>
                                        <div class="text-right text-muted">
                                            <small>{{$item->created_at->format('Y-m-d')}}</small>
                                        </div>
                                    </div>
                                    <p class="text-sm mb-0">{{$item->message}}</p>
                                </div>
                            </div>
                          </a>
                        @endif  
                        @endforeach             
                        @endif                                             
                    </div>
                    @if(count($notification)>0)
                    <a href="{{url('owner/notifications')}}" class="dropdown-item text-center text-primary font-weight-bold py-3">View all</a>
                    @else 
                    <a href="#" class="dropdown-item text-center text-primary font-weight-bold py-3">No data found</a>
                    @endif
                </div>
            </li>     
            @endif   
        
            <?php $lang = \App\Language::where('status',1)->get(); 
                  $icon =  \App\Language::where('name',session('locale'))->first(); 
                if($icon){
                    $lang_image="images/upload/".$icon->icon;
                }
                else{
                    $lang_image="images/flag-us.png";
                }
            ?>  
            
            <li class="nav-item dropdown">
                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">                       
                        <img alt="Image placeholder"  src="{{url($lang_image)}}" class="flag-icon" >                                             
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right py-0 overflow-hidden">

                    
                    @foreach ($lang as $item)
                    @if(Auth::check())
                    <a href="{{url('owner/changeOwnerLanguage/'.$item->name)}}" class="dropdown-item">
                    @else 
                    <a href="{{url('mainAdmin/changeLanguage/'.$item->name)}}" class="dropdown-item">
                    @endif                    
                        <img src="{{url('images/upload/'.$item->icon)}}" class="flag-icon" ><span>{{ __($item->name) }}</span> 
                    </a>                        
                    @endforeach                   
                </div>
            </li>  
            <li class="nav-item dropdown">
                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="rounded-circle">
                            <img alt="Image placeholder" class="avatar" src="{{url('images/upload/'.$image)}}">
                        </span>
                        <div class="media-body ml-2 d-none d-lg-block">
                            <span class="mb-0 text-sm  font-weight-bold">
                                @if(Auth::check())
                                {{Auth::user()->name}}
                                @elseif(Auth::guard('mainAdmin')->check()) 
                                {{Auth::guard('mainAdmin')->user()->name}}
                                @endif
                            </span>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right  py-0 overflow-hidden">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome') }}</h6>
                    </div>
                    @if(Auth::check())
                        <a href="{{url('owner/ownerProfile')}}" class="dropdown-item">
                            <i class="ni ni-single-02"></i>
                            <span>{{ __('My profile') }}</span>
                        </a>
                        <a href="{{url('owner/OwnerSetting')}}" class="dropdown-item">
                            <i class="ni ni-settings-gear-65"></i>
                            <span>{{ __('Settings') }}</span>
                        </a>
                    @elseif(Auth::guard('mainAdmin')->check()) 
                        <a href="{{url('mainAdmin/adminProfile')}}" class="dropdown-item">
                            <i class="ni ni-single-02"></i>
                            <span>{{ __('My profile') }}</span>
                        </a>
                        <a href="{{url('mainAdmin/adminSetting')}}" class="dropdown-item">
                            <i class="ni ni-settings-gear-65"></i>
                            <span>{{ __('Settings') }}</span>
                        </a>
                    @endif
                   
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item"  onclick="event.preventDefault();
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
    </div>
</nav>