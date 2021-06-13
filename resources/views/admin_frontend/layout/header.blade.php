<header role="banner">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
      <div class="container">
        <?php $logo = \App\CompanySetting::find(1)->logo; ?>
        <a class="navbar-brand" href="{{url('/')}}">
            <img src="{{ url('images/upload/'.$logo)}}" style="width: 200px;" alt="{{\App\CompanySetting::find(1)->name}}">                      
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample05" aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample05">      
          <ul class="navbar-nav ml-auto">
            <li class="nav-item cta-btn">
              <a class="nav-link" href="contact.html">Contact Us</a>
            </li>
          </ul>
          
        </div>
      </div>
    </nav>
  </header>
  <!-- END header -->