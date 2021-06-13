<footer class="footer">

    <div class="row align-items-center justify-content-xl-between">

        <div class="col-xl-8">

            <div class="copyright text-center text-xl-left text-muted">

                &copy; {{ now()->year }}  {{ __('Made with')}}  <i class="ni ni-favourite-28 text-danger"></i> {{ __('by')}}

            <a href="{{App\CompanySetting::find(1)->website}}" class="font-weight-bold ml-1" target="_blank">{{App\CompanySetting::find(1)->name}}</a>

            </div>

        </div>

        

    </div>

</footer>