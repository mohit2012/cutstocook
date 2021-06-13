<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ url('terms_and_condition') }}">Terms and condition</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ url('about_us') }}">about us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ url('privacy') }}">privacy policy</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-5 text-right">
                <a href="https://www.facebook.com/">
                    <i class="fa fa-facebook-square text-white"></i>
                </a>
                <a href="https://www.instagram.com/">
                    <i class="fa fa-instagram text-white"></i>
                </a>
                <a class="nav-link text-white ml-5 pr-0">&copy;  &copy; {{ now()->year }} {{App\CompanySetting::find(1)->name}}.ALL RIGHT RESERVED.</a>
            </div>
        </div>
    </div>
</footer>
