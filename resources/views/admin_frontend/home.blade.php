@extends('frontend.master')

@section('content')
    <div class="slider-wrap">
        <section class="home-slider owl-carousel">
  
  
          <div class="slider-item" style="background-image: url('{{url('frontend/images/banner.jpg')}}');">
            
            <div class="container">
              <div class="row slider-text align-items-center justify-content-center">
                <div class="col-md-10 text-center col-sm-12 ">
                  <h1 data-aos="fade-up">Enjoy Your Food with {{\App\CompanySetting::find(1)->name}}</h1>                
                </div>
              </div>
            </div>  
          </div>
  
          <div class="slider-item" style="background-image: url('{{url('frontend/images/banner1.jpg')}}');">
            <div class="container">
              <div class="row slider-text align-items-center justify-content-center">
                <div class="col-md-10 text-center col-sm-12 ">
                  <h1 data-aos="fade-up">Enjoy Your Food with {{\App\CompanySetting::find(1)->name}}</h1>                 
                </div>
              </div>
            </div>
            
          </div>
  
        </section>
      <!-- END slider -->
      </div> 
    
      
      <section class="section bg-light py-5  bottom-slant-gray">
        <div class="container service-section">
          <div class="row">
            <div class="col-md-6 mb-4 mb-lg-0 col-lg-3 text-center service-block" data-aos="fade-up" data-aos-delay="">
              <img src="{{url('frontend/images/menu.png')}}">
              <h3 class="mb-2 text-primary">Restaurant Menu</h3>
              <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
            </div>
            <div class="col-md-6 mb-4 mb-lg-0 col-lg-3 text-center service-block" data-aos="fade-up" data-aos-delay="100">
              
              <img src="{{url('frontend/images/star.png')}}">
              <h3 class="mb-2 text-primary">Rating & Reviews</h3>
              <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
            </div>
            <div class="col-md-6 mb-4 mb-lg-0 col-lg-3 text-center service-block" data-aos="fade-up" data-aos-delay="200">
              
              <img src="{{url('frontend/images/treck.png')}}">
              <h3 class="mb-2 text-primary">Order Tracking</h3>
              <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
            </div>
            <div class="col-md-6 mb-4 mb-lg-0 col-lg-3 text-center service-block" data-aos="fade-up" data-aos-delay="300">
              <img src="{{url('frontend/images/coupon.png')}}">
              <h3 class="mb-2 text-primary">Coupon Management</h3>
              <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-6 mb-4 mb-lg-0 col-lg-3 text-center service-block" data-aos="fade-up" data-aos-delay="">
              {{-- <span class="wrap-icon"><i class="fas fa-utensils d-block mb-4"></i></span> --}}
              <img src="{{url('frontend/images/order.png')}}">
              <h3 class="mb-2 text-primary">Nearby Restaurants</h3>
              <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
            </div>
            <div class="col-md-6 mb-4 mb-lg-0 col-lg-3 text-center service-block" data-aos="fade-up" data-aos-delay="100">
              {{-- <span class="wrap-icon"><i class="fas fa-bell d-block mb-4"></i></span> --}}
              <img src="{{url('frontend/images/notification.png')}}">
              <h3 class="mb-2 text-primary">Push Notifications</h3>
              <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
            </div>
            <div class="col-md-6 mb-4 mb-lg-0 col-lg-3 text-center service-block" data-aos="fade-up" data-aos-delay="200">
              {{-- <span class="wrap-icon"><i class="fas fa-globe d-block mb-4"></i></span> --}}
              <img src="{{url('frontend/images/social.png')}}">
              <h3 class="mb-2 text-primary">Social Media Login</h3>
              <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
            </div>
            <div class="col-md-6 mb-4 mb-lg-0 col-lg-3 text-center service-block" data-aos="fade-up" data-aos-delay="300">
              {{-- <span class="wrap-icon"><i class="fas fa-heart d-block mb-4"></i></span> --}}
              <img src="{{url('frontend/images/favourite.png')}}">
              <h3 class="mb-2 text-primary">Favorite Orders</h3>
              <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
            </div>
          </div>
        </div>
      </section>
    

      <section class="section pb-0">
        <div class="container">
          <div class="row mb-5 justify-content-center" data-aos="fade">
              {{-- <div class="col-md-7 text-center heading-wrap">
                <h2 data-aos="fade-up">The Restaurant</h2>
                <p data-aos="fade-up" data-aos-delay="100">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
              </div> --}}
            </div>
          <div class="row align-items-center">
            <div class="col-lg-6">
              <h2>Order food from favourite restaurants near you.</h2>

            </div>
            <div class="col-lg-6">
              <img src="{{url('frontend/images/dishes_2.jpg')}}" alt="Image" class="img-fluid about_img_1" data-aos="fade" data-aos-delay="300">
              {{-- <img src="{{url('frontend/images/about_2.jpg')}}" alt="Image" class="img-fluid about_img_1" data-aos="fade" data-aos-delay="400"> --}}
            </div>
            
          </div>
        </div>
      </section>
      
@endsection
    