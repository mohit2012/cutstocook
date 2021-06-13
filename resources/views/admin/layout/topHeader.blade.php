<div class="header pb-7 pt-5 pt-lg-8 d-flex align-items-center" style="background-image: url({{url('admin/images/profile-cover2.jpg')}}); background-size: cover; background-position: center center;">
    <span class="mask bg-gradient-default opacity-8"></span>
    <div class="container-fluid align-items-center">
        <div class="row align-items-center py-4">
            <div class="col-lg-9 col-9">
                <h6 class="h2 text-white d-inline-block mb-0" style="font-size:30px;">{{ $title }}</h6>
                <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                    <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                        @if(Auth::check())
                            <li class="breadcrumb-item text-white"><a href="{{url('owner/home')}}"><i class="fas fa-home"></i></a></li>
                        @elseif(Auth::guard('mainAdmin')->check())
                            <li class="breadcrumb-item text-white"><a href="{{url('mainAdmin/home')}}"><i class="fas fa-home"></i></a></li>
                        @endif
                        @if (isset($headerData) && $headerData)
                            <li class="breadcrumb-item text-white"><a href="{{url($url)}}">{{$headerData}}</a></li>
                        @endif
                            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
