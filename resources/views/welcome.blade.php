<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}" type="image/x-icon" />
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}" />
    <!-- ElegentIcon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/elegant-icons.min.css') }}" />
    <!-- Animate CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}" />
</head>

<body>
    <!-- Preloader -->
    {{--  <div id="preloader">
        <div id="ctn-preloader" class="ctn-preloader">
            <div class="round_spinner">
                <div class="spinner"></div>
                <div class="text">
                    <img class="mx-auto" src="{{ asset('assets/images/spinner_logo.svg') }}" alt="" />
                    <h4><span>Landpagy</span></h4>
                </div>
            </div>
            <h2 class="head">Did You Know?</h2>
            <p></p>
        </div>
    </div> --}}
    <!-- Header Area -->
    <header class="header-area">
        <nav class="navbar navbar-expand-lg menu_three sticky-nav">
            <div class="container-fluid">
                <a class="navbar-brand header_logo" href="index.html">
                    <img class="main_logo" src="{{ asset('assets/img/logo1.png') }}" alt="logo" />
                </a>
                <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="menu_toggle">
                        <span class="hamburger">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                        <span class="hamburger-cross">
                            <span></span>
                            <span></span>
                        </span>
                    </span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarText">
                    <ul class="navbar-nav menu mx-auto">
                        <li class="nav-item dropdown submenu mega-home active">
                            {{-- <a href="index.html" class="nav-link dropdown-toggle active">Home</a> --}}
                            {{-- <i class="arrow_carrot-right mobile_dropdown_icon" aria-hidden="false"
                                data-bs-toggle="dropdown"></i>
                            <ul class="dropdown-menu">
                                <li class="nav-item">
                                    <a href="index.html" class="nav-link">
                                        <img src="{{ asset('assets/images/home_demos/project.png') }}"
                                            alt="Demo" />
                                        <span>Project Management</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="index-software.html" class="nav-link">
                                        <img src="{{ asset('assets/images/home_demos/software.png') }}"
                                            alt="Demo" />
                                        <span>Software Company</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="index-software2.html" class="nav-link">
                                        <img src="{{ asset('assets/images/home_demos/software-2.png') }}"
                                            alt="Demo" />
                                        <span>Software Demo Landing</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="index-payment.html" class="nav-link">
                                        <img src="{{ asset('assets/images/home_demos/payment.png') }}"
                                            alt="Demo" />
                                        <span>Payment Processing</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="index-billing.html" class="nav-link">
                                        <img src="{{ asset('assets/images/home_demos/billing.png') }}"
                                            alt="Demo" />
                                        <span>Account Billing Software</span>
                                    </a>
                                </li>
                                <li class="nav-item active">
                                    <a href="index-cloud.html" class="nav-link">
                                        <img src="{{ asset('assets/images/home_demos/cloud.png') }}" alt="Demo" />
                                        <span>Cloud Saas</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="index-app.html" class="nav-link">
                                        <img src="{{ asset('assets/images/home_demos/app.png') }}" alt="Demo" />
                                        <span>Mobile App Landing</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="index-hrm.html" class="nav-link">
                                        <img src="{{ asset('assets/images/home_demos/hrm.png') }}" alt="Demo" />
                                        <span>HRM Software</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="index-pos.html" class="nav-link">
                                        <img src="{{ asset('assets/images/home_demos/pos.png') }}" alt="Demo" />
                                        <span>POS Software</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="index-proto.html" class="nav-link">
                                        <img src="{{ asset('assets/images/home_demos/proto.png') }}"
                                            alt="Demo" />
                                        <span>Prototype</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="help-center.html" class="nav-link">
                                        <img src="{{ asset('assets/images/home_demos/help.png') }}" alt="Demo" />
                                        <span>Help Center</span>
                                    </a>
                                </li>
                            </ul> --}}
                        </li>
                    </ul>
                    <div class="right-nav">
                        {{-- <a href="#" class="language-bar mr-50"><span class="active">En.</span>
                            <span>Ru</span></a> --}}
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}">Sign in</a>
                                @if (Route::has('register'))
                                    <a class="btn btn-red" href="{{ route('register') }}">Sign Up</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <!-- Header Area -->
    <main>
        <!-- Banner Area -->
        <section class="cloud-banner-area pt-160 pb-130">
            <div class="container position-relative">
                <div class="bg-shapes">
                    <div class="shape">
                        <img data-parallax='{"x":50, "y":70, "rotateY":0}'
                            src="{{ asset('assets/images/home_9/shapes/shape1.svg') }}" alt="" />
                    </div>
                    <div class="shape">
                        <img data-parallax='{"x":-50, "y":-40, "rotateY":0}'
                            src="{{ asset('assets/images/home_9/shapes/shape2.svg') }}" alt="" />
                    </div>
                    <div class="shape">
                        <img data-parallax='{"x":0, "y":30, "rotateY":0}'
                            src="{{ asset('assets/images/home_9/shapes/shape3.svg') }}" alt="" />
                    </div>
                    <div class="shape">
                        <img data-parallax='{"x":60, "y":60, "rotateY":0}'
                            src="{{ asset('assets/images/home_9/shapes/shape4.svg') }}" alt="" />
                    </div>
                    <div class="shape">
                        <img data-parallax='{"x":0, "y":-70, "rotateY":0}'
                            src="{{ asset('assets/images/home_9/shapes/shape5.svg') }}" alt="" />
                    </div>
                    <div class="shape">
                        <img src="{{ asset('assets/images/home_9/shapes/shape6.svg') }}" alt="" />
                    </div>
                    <div class="shape">
                        <img src="{{ asset('assets/images/home_9/shapes/shape7.svg') }}" alt="" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="cloud-banner-content text-center pt-80">
                            <h1 class="banner-title animatable fadeInDown mb-35" data-wow-delay="0.3s">
                                Manage Your
                                <span>Files
                                    <svg width="183" height="81" viewBox="0 0 183 81" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path class="animatable draw"
                                            d="M12.9677 22.1512C87.33 -23.7497 215.615 14.9169 173.556 52.8019C140.491 82.5856 -7.39191 98.5042 1.51001 44.3796C6.91522 11.5154 133.588 -8.53048 169.449 30.7166"
                                            stroke="#EFBA34" />
                                    </svg>
                                </span>
                                Effortlessly with Confidence
                            </h1>
                            <p class="banner-text wow fadeInDown" data-wow-delay="0.2s">
                                Arabiatalents Sign streamlines the process of sending and tracking agreements. Simply upload a CSV file with user details, select the recipients, and monitor the signing status with ease.
                            </p>
                            <p class="offer-text wow fadeInDown" data-wow-delay="0.1s">Optimized for Internal Use</p>
                            <a href="{{ route('dashboard') }}" class="btn btn-red wow fadeInDown" data-wow-delay="0.1s">Start</a>
                            <p class="meta-text wow fadeInDown" data-wow-delay="0.5s">A reliable solution designed to enhance efficiency
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Banner Area -->

    </main>


    <script src="{{ asset('assets/js/plugin/jquery-3.5.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/animation.gsap.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/wow.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery.parallax-scroll.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery.paroller.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>

</html>
