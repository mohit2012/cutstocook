

    <footer class="site-footer " role="contentinfo">

        <?php $data = \App\CompanySetting::find(1); ?>

        <div class="container pt-5">

          <div class="row" style="padding-top:140px;">

            <div class="col-md-4 mb-5">

              <h3>About Us</h3>

              <p class="mb-5">{{$data->description}}</p>

              <ul class="list-unstyled footer-link d-flex footer-social">

                <li><a href="#" class="p-2"><i class="fab fa-twitter"></i></a></li>

                <li><a href="#" class="p-2"><i class="fab fa-facebook-f"></i></a></li>

                <li><a href="#" class="p-2"><i class="fab fa-linkedin-in"></i></a></li>

                <li><a href="#" class="p-2"><i class="fab fa-instagram"></i></a></li>

              </ul>

  

            </div>

           

            <div class="col-md-4 mb-5 pl-5">

              <h3>Quick Links</h3>

              <ul class="list-unstyled footer-link">

                <li><a href="#">About</a></li>

                <li><a href="#">Terms of Use</a></li>

                <li><a href="#">Disclaimers</a></li>

                <li><a href="#">Contact</a></li>

              </ul>

            </div>

            <div class="col-md-4">

                <div>

                    <h3>DownLoad App</h3>

                    <ul class="list-unstyled footer-link">

                        <li class="d-block">

                        <img src="{{url('frontend/images/Apple-Store.png')}}"> 

                        </li>

                        <li class="d-block">

                            <img src="{{url('frontend/images/android-Playstore.png')}}"> 

                        </li>

                      

                    </ul>

                  </div>

            </div>

          </div>

          <div class="row">

            <div class="col-12 text-md-center text-left">

              <p>            
              Copyright &copy;<script>document.write(new Date().getFullYear());</script> made with <i class="fa fa-heart text-danger" aria-hidden="true"></i> by <a href="http://saasmonks.in/" target="_blank" >Saas Monks</a>          
              </p>

            </div>

          </div>

        </div>

      </footer>

      <!-- END footer -->