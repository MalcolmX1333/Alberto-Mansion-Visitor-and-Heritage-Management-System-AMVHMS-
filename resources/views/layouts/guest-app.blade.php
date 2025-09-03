<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Mobile Specific Meta -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{env('APP_NAME')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/fav.png">

    <!-- meta character set -->
    <meta charset="UTF-8">

    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">
    <!--
    CSS
    ============================================= -->
    <link rel="stylesheet" href="{{ asset('landing-page/css/linearicons.css') }}">
    <link rel="stylesheet" href="{{ asset('landing-page/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing-page/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('landing-page/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('landing-page/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('landing-page/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing-page/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('landing-page/css/main.css') }}">

</head>
<body>
<header id="header" id="home">
    <div class="container header-top">
        <div class="row">
            <div class="col-6 top-head-left">
                <ul>
                    <li><a href="#">Visit Alberto Mansion</a></li>
                </ul>
            </div>
            <div class="col-6 top-head-right">
                <ul>
                    @auth
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-user-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="">
                                    <i class="fa fa-user"></i> Profile
                                </a>
                                <a class="dropdown-item" href="{{route('guest.reservation.index')}}">
                                    <i class="fa fa-calendar"></i> Reservations
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out"></i> Logout
                                </a>
                                <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Sign Up</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </div>
    <hr>
    <div class="container">
        <div class="row align-items-center justify-content-center d-flex">
            <!-- Left Navigation Items -->
            <div class="col-auto">
                <nav class="nav-menu-left">
                    <ul class="nav-menu d-flex">
                        <li class="menu-active"><a href="#">Home</a></li>
                        <li><a href="#">Gallery</a></li>
                    </ul>
                </nav>
            </div>

            <!-- Center Logo -->
            <div class="col-auto mx-4">
                <div id="logo" class="text-center">
                    <img src="{{asset('binanlogo.png')}}" alt="Alberto Mansion Logo" class="circular-logo">
                </div>
            </div>

            <!-- Right Navigation Items -->
            <div class="col-auto">
                <nav class="nav-menu-right">
                    <ul class="nav-menu d-flex">
                        <li><a href="#">Heritage</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header><!-- #header -->

@yield('content')

<!-- start footer Area -->
<footer class="footer-area section-gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="single-footer-widget">
                    <h6>About Us</h6>
                    <p>
                        <img src="{{ asset('alberto_mansion_logo.png') }}" alt="Alberto Mansion Logo" style="max-width: 150px;">
                    </p>
                    <p>
                        <strong>Heritage Team</strong><br>
                        Alberto Mansion Museum<br>
                        Binan City, Laguna<br>
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="single-footer-widget">
                    <h6>Contact Us</h6>
                    <p>
                        Alberto Mansion Museum<br>
                        Binan City, Laguna<br>
                        Philippines
                    </p>
                    <p>
                        Heritage Curator<br>
                        Contact Person
                    </p>
                    <p>
                        info@albertomansion.ph<br>
                        Send us your query anytime!
                    </p>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6 social-widget">
                <div class="single-footer-widget">
                    <h6>Follow Us</h6>
                    <p>Connect with our heritage community</p>
                    <div class="footer-social d-flex align-items-center">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                    </div>
                </div>
            </div>


            @guest()
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="single-footer-widget">
                    <h6>Links</h6>
                    <a href="{{route('login')}}" class="btn btn-primary">Login</a>
                </div>
            </div>
            @endguest
        </div>
    </div>
</footer>

<script src="{{ asset('landing-page/js/vendor/jquery-2.2.4.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="{{ asset('landing-page/js/vendor/bootstrap.min.js') }}"></script>
<script src="{{ asset('landing-page/js/easing.min.js') }}"></script>
<script src="{{ asset('landing-page/js/hoverIntent.js') }}"></script>
<script src="{{ asset('landing-page/js/superfish.min.js') }}"></script>
<script src="{{ asset('landing-page/js/jquery.ajaxchimp.min.js') }}"></script>
<script src="{{ asset('landing-page/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('landing-page/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('landing-page/js/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('landing-page/js/justified.min.js') }}"></script>
<script src="{{ asset('landing-page/js/jquery.sticky.js') }}"></script>
<script src="{{ asset('landing-page/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('landing-page/js/parallax.min.js') }}"></script>
<script src="{{ asset('landing-page/js/mail-script.js') }}"></script>
<script src="{{ asset('landing-page/js/main.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('scripts')
</body>
</html>
