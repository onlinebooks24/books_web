@extends('layouts.master')

@section('title')
    Online Books Review
@endsection

@section('run_custom_css_file')
    <!-- Icon Fonts -->
    <link href="{{ asset('css/eleganticons.css') }}" rel="stylesheet">

    <!-- Plugins -->
    <link href="{{ asset('css/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vertical.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">

    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/template.css') }}">
    <link href="{{ asset('css/vertical.min.css') }}" rel="stylesheet">
    
    <link rel="stylesheet" type="text/css" href="{{ asset('css/template.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css">

@endsection

@section('content')
    <!-- PRELOADER -->
    <div class="page-loader">
        <div class="loader">Loading...</div>
    </div>
    <!-- /PRELOADER -->

    <div class="wrapper">

        <!-- HERO -->
        <section id="hero" class="module-hero overlay-light" data-background="{{ asset('images/site_template/module-1.jpg') }}">
        @include('includes.header')

            <!-- HERO TEXT -->
            <div class="hero-caption">
                <div class="hero-text">

                    <!-- YOUR LOGO -->
                    <h1>OnlineBooksReview</h1>
                    <img class="m-b-100" src="{{ asset('images/logo.png') }}" alt="">

                    <!-- HERO CONTENT -->
                    <h1 class="m-b-40">Coming <b>soon.</b></h1>
                    <p class="lead m-b-60"> Server Editor Linux Panel <br class="hidden-xs"> Comming Soon </p>


                </div>
            </div>
            <!-- /HERO TEXT -->

        </section>
        <!-- /HERO -->

        <!-- SERVICES -->
        <section id="services" class="module">

            <!-- MODULE DIVIDER -->
            <a id="hero-divider" class="striped-icon divider inner-scroll" href="#services">
                <i class="fa fa-angle-down"></i>
            </a>
            <!-- /MODULE DIVIDER -->

            <div class="container">

                <!-- MODULE TITLE -->
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">
                        <h4 class="text-center">The core features includes Web Server, Domain, Subdomain, Mysql, File Manager, Advanced DNS, Email Manager, Cron Job, FTP and User Management.</h4>
                    </div>
                </div>
                <!-- /MODULE TITLE -->

                <div class="row multi-columns-row">

                    <!-- ICONBOX -->
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <div class="iconbox text-sm-center wow fadeInUp">
                            <div class="iconbox-icon">
                                <span class="icon_desktop"></span>
                            </div>
                            <div class="iconbox-header">
                                <h4 class="iconbox-title">Easily System Setup</h4>
                            </div>
                            <div class="iconbox-content">
                                <p>Simply pick your configuration and develop your app. No need to spend valuable development time on system setup and maintenance.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- /ICONBOX -->

                    <!-- ICONBOX -->
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <div class="iconbox text-sm-center wow fadeInUp" data-wow-delay="0.2s">
                            <div class="iconbox-icon">
                                <span class="icon_like"></span>
                            </div>
                            <div class="iconbox-header">
                                <h4 class="iconbox-title">Perfect design</h4>
                            </div>
                            <div class="iconbox-content">
                                <p>The app look and feel is taken as important as its feature set. The navigation feels like second nature along with visual appearance, interactive behavior and assistive capabilities.</p>
                            </div>
                        </div>
                    </div>
                    <!-- /ICONBOX -->

                    <!-- ICONBOX -->
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <div class="iconbox text-sm-center wow fadeInUp" data-wow-delay="0.4s">
                            <div class="iconbox-icon">
                                <span class="icon_clock_alt"></span>
                            </div>
                            <div class="iconbox-header">
                                <h4 class="iconbox-title">Fast remote access</h4>
                            </div>
                            <div class="iconbox-content">
                                <p>An admin panel that providing you with a fast secure way to manage a remote Linux server at any time using everyday tools like a web terminal, text editor, file manager and others.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- /ICONBOX -->

                    <!-- ICONBOX -->
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <div class="iconbox text-sm-center wow fadeInUp">
                            <div class="iconbox-icon">
                                <span class="icon_search"></span>
                            </div>
                            <div class="iconbox-header">
                                <h4 class="iconbox-title">Modular architecture</h4>
                            </div>
                            <div class="iconbox-content">
                                <p>You can replace or add any one component (module) without affecting the rest of the system.</p>
                            </div>
                        </div>
                    </div>
                    <!-- /ICONBOX -->

                    <!-- ICONBOX -->
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <div class="iconbox text-sm-center wow fadeInUp" data-wow-delay="0.2s">
                            <div class="iconbox-icon">
                                <span class="icon_lightbulb_alt"></span>
                            </div>
                            <div class="iconbox-header">
                                <h4 class="iconbox-title">Powerful File Manager</h4>
                            </div>
                            <div class="iconbox-content">
                                <p>Code on our web-based editor, with your favorite text editor or via SSH. Your development box is accessible anywhere.</p>
                            </div>
                        </div>
                    </div>
                    <!-- /ICONBOX -->

                    <!-- ICONBOX -->
                    <div class="col-sm-4 col-md-4 col-lg-4">
                        <div class="iconbox text-sm-center wow fadeInUp" data-wow-delay="0.4s">
                            <div class="iconbox-icon">
                                <span class="icon_ribbon_alt"></span>
                            </div>
                            <div class="iconbox-header">
                                <h4 class="iconbox-title">Fast support</h4>
                            </div>
                            <div class="iconbox-content">
                                <p>If you are facing any problem, you can contact with us. we will give you support via email, chat or teamviewer.</p>
                            </div>
                        </div>
                    </div>
                    <!-- /ICONBOX -->

                </div>

            </div>
        </section>
        <!-- /SERVICES -->

    

        <!-- CONTACT -->
        <section id="contact" class="module-sm">

            <!-- MODULE DIVIDER -->
            <a class="striped-icon divider inner-scroll" href="#contact">
                <i class="fa fa-envelope-o"></i>
            </a>
            <!-- /MODULE DIVIDER -->

            <div class="container">

                <div class="row">

                    <div class="col-sm-8 col-sm-offset-2">
                        @if(Session::has('message'))
                            @section('run_custom_jquery')
                            <script>
                            $(document).ready(function(){                        
                                swal({
                                      title: "Congratulations!",
                                      text: "Your mail was sent!",
                                      type: "success",   
                                    });
                            });
                            </script>
                        @endsection
                         @endif
                        <!-- CONTACT FORM -->
                        <form id="submit" method="get" action="{{ route('mail')}}">

                            <div class="row">

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="sr-only" for="name">Name</label>
                                        <input type="text" id="name" class="form-control" name="name" placeholder="Name*" required="" data-validation-required-message="Please enter your name.">
                                        <p class="help-block text-danger"></p>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="sr-only" for="email">Your Email</label>
                                        <input type="email" id="email" name="email" class="form-control" placeholder="E-mail*" required="" data-validation-required-message="Please enter your email address.">
                                        <p class="help-block text-danger"></p>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="sr-only" for="subject">Subject</label>
                                        <input type="text" id="subject" class="form-control" name="subject" placeholder="Subject*" required="" data-validation-required-message="Please enter subject.">
                                        <p class="help-block text-danger"></p>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <textarea class="form-control" id="message" name="message" rows="7" placeholder="Message*" required="" data-validation-required-message="Please enter your message."></textarea>
                                        <p class="help-block text-danger"></p>
                                    </div>

                                    <div class="text-center wow tada" data-wow-delay="0.5s">
                                        <button type="submit" class="btn btn-base">Send Massage</button>
                                    </div>
                                </div>

                            </div>

                        </form>
                        <!-- /CONTACT FORM -->

                        <!-- Ajax response -->
                        <div id="contact-response" class="ajax-response"></div>

                    </div>

                </div><!-- .row -->

            </div>

        </section>
        <!-- /CONTACT -->

        <!-- FOOTER -->

        <footer class="footer p-t-0">
            <div class="container">

                <div class="row">

                    <div class="col-sm-2 col-sm-offset-5">
                        <div class="text-center m-b-40">
                            <img src="{{ asset('images/logo.png') }}" alt="">
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-sm-12">

                        <div class="social-icons social-icons-animated m-b-40">
                            <a href="https://facebook.com/onlinebooksreview" target="_blank" class="fa fa-facebook facebook wow fadeInUp"></a>
                            <a href="#" target="_blank" class="fa fa-twitter twitter wow fadeInUp"></a>
                            <a href="#" target="_blank" class="fa fa-google-plus google-plus wow fadeInUp"></a>
                            <a href="#" target="_blank" class="fa fa-instagram instagram wow fadeInUp"></a>
                            <a href="#" target="_blank" class="fa fa-behance behance wow fadeInUp"></a>
                            <a href="#" target="_blank" class="fa fa-dribbble dribbble wow fadeInUp"></a>
                            <a href="#" target="_blank" class="fa fa-flickr flickr wow fadeInUp"></a>
                            <a href="#" target="_blank" class="fa fa-foursquare foursquare wow fadeInUp"></a>
                        </div>

                    </div>

                </div>

                <hr class="divider">

                <div class="row">

                    <div class="col-sm-12">

                        <div class="copyright text-center m-t-40">
                            © 2017 <a href="#"><b style="color: #87B832">OnlineBooksReview</b></a>, All Rights Reserved.
                        </div>

                    </div>

                </div>

            </div>
        </footer>

        <!-- /FOOTER -->

    </div>
    <!-- /WRAPPER -->
@endsection

@section('run_custom_js_file')
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/jquery.fitvids.js') }}"></script>

    <script src="{{ asset('js/wow.min.js') }}"></script>


    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="{{ asset('js/jquery.countdown.min.js') }}"></script>

    <script src="{{ asset('js/jquery.mb.YTPlayer.min.js') }}"></script>

    <script src="{{ asset('js/jqBootstrapValidation.js') }}"></script>
    <script src="{{ asset('js/smoothscroll.js') }}"></script>
    <script src="{{ asset('js/contact.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>
@endsection

