{{--@if(route('blog.index') == Request::url())--}}
{{--<!-- Preloader -->--}}
{{--<div class="loader-mask">--}}
  {{--<div class="loader">--}}
    {{--<div></div>--}}
  {{--</div>--}}
{{--</div>--}}
{{--@endif--}}

<!-- Bg Overlay -->
<div class="content-overlay"></div>

<!-- Subscribe Modal -->
<div class="modal fade" id="subscribe-modal" tabindex="-1" role="dialog" aria-labelledby="subscribeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="subscribeModalLabel">Subscribe for Newsletter</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="mc4wp-form" method="post">
          <div class="mc4wp-form-fields">
            <p>
              <i class="mc4wp-form-icon ui-email"></i>
              <input type="email" name="EMAIL" placeholder="Your email" required="">
            </p>
            <p>
              <input type="submit" class="btn btn-md btn-color btn-subscribe" value="Subscribe">
            </p>
          </div>
        </form>
      </div>
    </div>
  </div>
</div> <!-- end subscribe modal -->


<!-- Mobile Sidenav -->
<header class="sidenav" id="sidenav">
  <!-- Search -->
  <div class="sidenav__search-mobile">
    <form method="get" class="sidenav__search-mobile-form">
      <input type="search" class="sidenav__search-mobile-input" placeholder="Search..." aria-label="Search input">
      <button type="submit" class="sidenav__search-mobile-submit" aria-label="Submit search">
        <i class="ui-search"></i>
      </button>
    </form>
  </div>

  <nav>
    <ul class="sidenav__menu" role="menubar">

      <li>
        <a href="{{ route('blog.index') }}" class="sidenav__menu-link">Home</a>
      </li>

      <li>
        <a href="/advertise-us" class="sidenav__menu-link">Advertise Us</a>
      </li>

      <li>
        <a href="/privacy-policy" class="sidenav__menu-link">Privacy Policy</a>
      </li>

      <li>
        <a href="/contact" class="sidenav__menu-link">Request for books review</a>
      </li>

    </ul>
  </nav>

  <div class="socials sidenav__socials ">
    <a class="social-facebook" href="https://facebook.com/onlinebooksreview" target="_blank" aria-label="facebook">
      <i class="ui-facebook"></i>
    </a>
    <a class="social-twitter" href="https://twitter.com/onlinebooks24" target="_blank" aria-label="twitter">
      <i class="ui-twitter"></i>
    </a>
    <a class="social-youtube" href="https://www.youtube.com/channel/UCFoPgOTE5HZ4yz5iB_g8WkA" target="_blank" aria-label="youtube">
      <i class="ui-youtube"></i>
    </a>
  </div>
</header> <!-- end mobile sidenav -->

<main class="main oh" id="main">

<!-- Navigation -->
<header class="nav">

  <div class="nav__holder nav--sticky">
    <div class="container relative">

      <div class="flex-parent">

        <!-- Mobile Menu Button -->
        <button class="nav-icon-toggle" id="nav-icon-toggle" aria-label="Open mobile menu">
            <span class="nav-icon-toggle__box">
              <span class="nav-icon-toggle__inner"></span>
            </span>
        </button> <!-- end mobile menu button -->

        <!-- Logo -->
        <a href="{{ route('blog.index') }}" style="color: white; font-size: 20px; font-weight: bold" class="logo">
          Online Books Review
        </a>

        <!-- Nav-wrap -->
        <nav class="flex-child nav__wrap d-none d-lg-block">
          <ul class="nav__menu">

            <li class="nav__dropdown">
              <a href="{{ route('blog.index') }}">Home</a>
            </li>

            <li>
              <a href="/advertise-us">Advertise Us</a>
            </li>

            <li>
              <a href="/privacy-policy">Privacy Policy</a>
            </li>

            <li class="btn ">
              <a href="/contact">Request for books review</a>
            </li>

          </ul> <!-- end menu -->
        </nav> <!-- end nav-wrap -->

        <!-- Nav Right -->
        <div class="nav__right nav--align-right d-none d-lg-flex">

          <!-- Socials -->
          <div class="nav__right-item socials socials--nobase nav__socials ">
            <a class="social-linkedin" href="https://www.linkedin.com/company-beta/13346322" target="_blank">
              <i class="ui-linkedin"></i>
            </a>
            <a class="social-pinterest" href="https://www.pinterest.com/onlinebooksr/" target="_blank">
              <i class="ui-pinterest"></i>
            </a>
            <a class="social-facebook" href="https://facebook.com/onlinebooksreview" target="_blank">
              <i class="ui-facebook"></i>
            </a>
            <a class="social-twitter" href="https://twitter.com/onlinebooks24" target="_blank">
              <i class="ui-twitter"></i>
            </a>
            <a class="social-youtube" href="https://www.youtube.com/channel/UCFoPgOTE5HZ4yz5iB_g8WkA" target="_blank">
              <i class="ui-youtube"></i>
            </a>
            <a class="social-google-plus" href="https://plus.google.com/b/110233331450185953116/" target="_blank">
              <i class="ui-google-plus"></i>
            </a>
          </div>

          <div class="nav__right-item">
            <a href="" class="nav__subscribe btn-subscribe" data-toggle="modal" data-target="#subscribe-modal">Subscribe</a>
          </div>

          <!-- Search -->
          <div class="nav__right-item nav__search">
            <a href="#" class="nav__search-trigger" id="nav__search-trigger">
              <i class="ui-search nav__search-trigger-icon"></i>
            </a>
            <div class="nav__search-box" id="nav__search-box">
              <form class="nav__search-form">
                <input type="text" placeholder="Search an article" class="nav__search-input">
                <button type="submit" class="nav__search-button btn btn-md btn-color btn-button">
                  <i class="ui-search nav__search-icon"></i>
                </button>
              </form>
            </div>

          </div>

        </div> <!-- end nav right -->

      </div> <!-- end flex-parent -->
    </div> <!-- end container -->

  </div>
</header> <!-- end navigation -->

<div class="main-container" id="main-container">

