<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="baseUrl" content="{{ url('/') }}">
        <script src="{{url('admin/js/jquery.min.js')}}"></script>
        <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
        <script>
            if($('#app_id_web').val()!=null){
            var OneSignal = window.OneSignal || [];
            if(OneSignal.length>0){
                setTimeout(() => {
                    OneSignal.getRegistrationId(function(status)
                    {
                        console.log('status',status);
                    });
                }, 2000);
                OneSignal.push(function() {
                    OneSignal.init({
                        appId: $('#app_id_web').val(),
                    });
                    OneSignal.getUserId().then(function(userId) {
                        console.log("OneSignal User ID:", userId);

                        $.ajax({
                            headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type:"POST",
                            url:window.location.origin+'/getDeviceToken',
                            data:{
                            id:userId,
                            },
                            success: function(result){
                            console.log('result ',result)
                            },
                            error: function(err){
                            console.log('err ',err)
                            }
                        });
                    });
                });
            }
            }
        </script>
        <title>{{\App\CompanySetting::find(1)->name}}</title>
        <link href="{{ url('images/upload/'.\App\CompanySetting::find(1)->favicon)}}" rel="icon" type="image/png">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
        <link href="{{url('admin/css/nucleo.css')}}" rel="stylesheet">
        <link href="https://jvectormap.com/css/jquery-jvectormap-2.0.3.css" rel="stylesheet">
        <link href="{{url('admin/css/all.min.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="{{url('admin/css/sweetalert2.scss')}}">
        <link href="{{url('admin/css/animate.css')}}" id="theme" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="{{url('admin/css/bootstrap-wysihtml5.css')}}" />
        <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" id="theme" rel="stylesheet">
        <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" id="theme" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css">
        <link type="text/css" href="{{url('admin/css/argon.css?v=1.0.0')}}" rel="stylesheet">
        <link href="{{url('admin/css/custom.css')}}" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
        <script src="https://www.gstatic.com/firebasejs/8.3.1/firebase-app.js"></script>
        <script src="{{ url('admin/js/firebase-messaging.js') }}"></script>
        @php
            $setting = App\Setting::find(1);
            $apikey = $setting->apiKey;
            $authDomain = $setting->authDomain;
            $projectId = $setting->projectId;
            $storageBucket = $setting->storageBucket;
            $messagingSenderId = $setting->messagingSenderId;
            $appId = $setting->appId;
            $measurementId = $setting->measurementId;
        @endphp
        <script>
            var firebaseConfig =
            {
              apiKey: "<?php echo $apikey ?>",
              authDomain: "<?php echo $authDomain ?>",
              projectId: "<?php echo $projectId ?>",
              storageBucket: "<?php echo $storageBucket ?>",
              messagingSenderId: "<?php echo $messagingSenderId ?>",
              appId: "<?php echo $appId ?>",
              measurementId: "<?php echo $measurementId ?>"
            };
            firebase.initializeApp(firebaseConfig);
            // firebase.analytics();
        </script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    </head>
    <body class="{{ $class ?? '' }}">
        <input type="hidden" value="{{url('/')}}" id="base-url">
        <input type="hidden" value="{{\App\Setting::find(1)->web_onesignal_app_id}}" id="app_id_web">
        <input type="hidden" value="{{Auth::check()?1:0}}" id="auth_role">
        @if(Auth::check())
            @include('admin.layout.sidebar')
            <div class="main-content">
                @include('admin.layout.header')
                @yield('content')
                @include('admin.layout.footer')
            </div>
        @elseif(Auth::guard('mainAdmin')->check())
        <?php $status = \App\Setting::find(1)->license_status; ?>
            @include('admin.layout.sidebar')
            <div class="main-content">
                @include('admin.layout.header')
                    @if($status==1)
                        @yield('content')
                        @yield('content_setting')
                    @else
                        <script>
                            url = window.location.origin+window.location.pathname;
                            var a = $('#base-url').val()+'/mainAdmin/adminSetting';
                            if(a != url){
                                setTimeout(() =>
                                {
                                    Swal.fire({
                                        title: 'Your License is deactivated!',
                                        type: 'info',
                                        html:  'to get benefit of Foodeli please activate your license<br><br> '+
                                        '<a href="'+a+'" style="background:#3085d6;color:#fff;padding:8px 10px;border-radius:5px;">Activate License</a>',
                                        showCloseButton: false,
                                        showCancelButton: false,
                                        showConfirmButton: false,
                                        focusConfirm: false,
                                        onClose: () => {
                                            window.location.replace(a);
                                        }
                                    })
                                }, 500);
                            }
                        </script>
                        @yield('content_setting')
                    @endif
                    @include('admin.layout.footer')
            </div>
        @else
            <div class="main-content">
                @yield('content')
            </div>
        @endif

        <script src="{{url('admin/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{url('admin/js/sweetalert.all.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        @stack('js')
        <script src="{{url('admin/js/argon.js?v=1.0.0')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
        <script src="{{url('admin/js/wysihtml5-0.3.0.js')}}"></script>
        <script src="{{url('admin/js/bootstrap-wysihtml5.js')}}"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
        <script src="{{url('admin/js/notify.js')}}"></script>
        <?php
        $key = \App\Setting::find(1)->map_key;
        ?>
        <script src="https://maps.googleapis.com/maps/api/js?key={{$key}}" async defer></script>
        {{-- <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script> --}}
        <script src="{{url('admin/js/jquery-jvectormap.min.js')}}"></script>
        <script src="{{url('admin/js/jquery-jvectormap-world-mill.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
        <script src="{{url('admin/js/googleMap.js')}}"></script>
        <script src="{{url('admin/js/charts.js')}}"></script>
        <script src="{{url('admin/js/lightbox.js')}}"></script>
        <script src="{{url('admin/js/custom.js')}}"></script>
    </body>
    @yield('scripts')
</html>
