<!Doctype html>
<html class="no-js" lang="en-US">

<head>
  <title>@yield('title')</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" >
    <meta name="keywords" content="@yield('meta_keyword', 'best books, review, top books, best books review');" >
    <meta name="author" content="OnlineBooksReview" >
    <meta name="description" content="@yield('meta_description' , 'You will get reviews and recommendation of best online books. Get suggestion of buying best books from online.')">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:locale" content="en_US" >
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.ico') }}">
    <meta name="google-site-verification" content="SshyPvUWMt9RvlmGX7X4AJtFpVhrCiejltTVOqRbe6o" />
    <meta name="msvalidate.01" content="44E29CA719A9C2074B87ECF80ADDF5FD" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="www.onlinebooksreview.com">
    <meta property="og:image" content="" >
    <meta property="og:url" content="{{ url()->current() }}">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
     @yield('run_custom_css_file')
    @yield('run_custom_css')
</head>
	<body>	

    @yield('content')

    <script type="text/javascript" src="{{ asset('js/jquery-2.1.4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js')}}"></script>

    @if (env('APP_ENV') == 'production' && empty(Auth::user()))
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-97703152-1', 'auto');
            ga('send', 'pageview');
        </script>
    @endif

    @yield('run_custom_js_file')
    @yield('run_custom_jquery')
	</body>
</html>
