<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>ES Group Company</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
  
    <!-- Favicons -->
    <link href="{{ URL::to('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ URL::to('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
  
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  
    <!-- Vendor CSS Files -->
    <link href="{{ URL::to('assets/css/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('assets/css/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ URL::to('assets/css/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ URL::to('assets/css/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('assets/css/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
  
    <!-- Main CSS File -->
    <link href="{{ URL::to('assets/css/main.css') }}" rel="stylesheet">
</head>

<body>

    <!-- Header -->
    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
    
          <a href="{{ route('mainPages') }}" class="logo d-flex align-items-center me-auto me-xl-0">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <img src="{{ URL::to('assets/img/ESGroup-Logo.png') }}" alt="">
          </a>
    
          <nav id="navmenu" class="navmenu">
            <ul>
              <li><a href="#hero" class="active">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#features">Features</a></li>
              <li><a href="#services">Services</a></li>
              <li><a href="#pricing">Pricing</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
          </nav>
    
          <a class="btn-getstarted" href="{{ route('mainPages') }}">Back</a>
    
        </div>
      </header>
    <!-- End Header -->

    <!-- Main Content -->
    <main class="main">
    <!-- Page Title -->
    <div class="page-title light-background">
        <div class="container">
          <h1>Sign in</h1>
          <nav class="breadcrumbs">
            <ol>
              <li><a href="{{ route('mainPages') }}">Home</a></li>
                <li class="current">Admin Page</li>
                <!-- Start Login Pages -->
                    <div class="container d-flex justify-content-center align-items-center" style="height: 50vh;">
                        <div class="col-md-5">
                            <div class="card p-4 shadow">
                                <div class="text-center mb-4">
                                    <h3>Access to our Dashboard</h3>
                                </div>
                                <!-- Login Form -->
                                <form method="POST" action="{{ route('login') }}">
                                  @csrf
                                  <div class="form-group mb-4">
                                      <label for="email" class="form-label" style="text-align: left;">Email</label>
                                      <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter email" value="{{ old('email') }}" style="height: 50px; width: 80%; margin: 0 auto;">
                                      @error('email')
                                          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                      @enderror
                                  </div>
                              
                                  <div class="form-group mb-4">
                                      <label for="password" class="form-label" style="text-align: left;">Password</label>
                                      <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" style="height: 50px; width: 80%; margin: 0 auto;">
                                      @error('password')
                                          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                      @enderror
                                  </div>
                              
                                  {{-- <div class="form-group mb-3 text-end">
                                      <a href="{{ route('forget-password') }}" class="text-muted">Forgot password?</a>
                                  </div> --}}
                              
                                  <div class="form-group d-grid" style="width: auto;">
                                    <center>
                                      <button type="submit" class="btn btn-primary">Login</button>
                                    </center>
                                  </div>
                              </form>
                              
                              
                                <!-- End Login Form -->
                            </div>
                        </div>
                    </div>
                </ol>
            </nav>
        </div>
      </div><!-- End Page Title -->
  
      <!-- Starter Section Section -->
      <section id="starter-section" class="starter-section section">
  
        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
          <h2>Starter Section</h2>
          <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
        </div><!-- End Section Title -->
  
        <div class="container" data-aos="fade-up">
          <p>Use this page as a starter for your own custom pages.</p>
        </div>
  
      </section><!-- /Starter Section Section -->
    </main>
    <!-- End Main Content -->

    <footer id="footer" class="footer">

        <div class="container footer-top">
          <div class="row gy-4">
            <div class="col-lg-4 col-md-6 footer-about">
              <a href="index.html" class="logo d-flex align-items-center">
                <span class="sitename">iLanding</span>
              </a>
              <div class="footer-contact pt-3">
                <p>A108 Adam Street</p>
                <p>New York, NY 535022</p>
                <p class="mt-3"><strong>Phone:</strong> <span>+1 5589 55488 55</span></p>
                <p><strong>Email:</strong> <span>info@example.com</span></p>
              </div>
              <div class="social-links d-flex mt-4">
                <a href=""><i class="bi bi-twitter-x"></i></a>
                <a href=""><i class="bi bi-facebook"></i></a>
                <a href=""><i class="bi bi-instagram"></i></a>
                <a href=""><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
    
            <div class="col-lg-2 col-md-3 footer-links">
              <h4>Useful Links</h4>
              <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">About us</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Terms of service</a></li>
                <li><a href="#">Privacy policy</a></li>
              </ul>
            </div>
    
            <div class="col-lg-2 col-md-3 footer-links">
              <h4>Our Services</h4>
              <ul>
                <li><a href="#">Web Design</a></li>
                <li><a href="#">Web Development</a></li>
                <li><a href="#">Product Management</a></li>
                <li><a href="#">Marketing</a></li>
                <li><a href="#">Graphic Design</a></li>
              </ul>
            </div>
    
            <div class="col-lg-2 col-md-3 footer-links">
              <h4>Hic solutasetp</h4>
              <ul>
                <li><a href="#">Molestiae accusamus iure</a></li>
                <li><a href="#">Excepturi dignissimos</a></li>
                <li><a href="#">Suscipit distinctio</a></li>
                <li><a href="#">Dilecta</a></li>
                <li><a href="#">Sit quas consectetur</a></li>
              </ul>
            </div>
    
            <div class="col-lg-2 col-md-3 footer-links">
              <h4>Nobis illum</h4>
              <ul>
                <li><a href="#">Ipsam</a></li>
                <li><a href="#">Laudantium dolorum</a></li>
                <li><a href="#">Dinera</a></li>
                <li><a href="#">Trodelas</a></li>
                <li><a href="#">Flexo</a></li>
              </ul>
            </div>
    
          </div>
        </div>
    
        <div class="container copyright text-center mt-4">
          <p>© <span>Copyright</span> <strong class="px-1 sitename">iLanding</strong> <span>All Rights Reserved</span></p>
          <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you've purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
          </div>
        </div>
    
      </footer>
    
      <!-- Scroll Top -->
      <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS -->
    <script src="{{ URL::to('assets/css/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::to('assets/css/php-email-form/validate.js') }}"></script>
    <script src="{{ URL::to('assets/css/aos/aos.js') }}"></script>
    <script src="{{ URL::to('assets/css/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ URL::to('assets/css/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ URL::to('assets/css/purecounter/purecounter_vanilla.js') }}"></script>
  
    <!-- Main JS File -->
    <script src="{{ URL::to('assets/js/main.js') }}"></script>
</body>

</html>
