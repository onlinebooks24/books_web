<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>

    <meta charset="utf-8">
    <meta name="keywords" content="@yield('meta_keyword', 'best books, review, top books, best books review');" >
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="@yield('meta_description' , 'You will get reviews and recommendation of best online books. Get suggestion of buying best books from online.')">

    <meta property="og:locale" content="en_US" >
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.ico') }}">
    <meta name="google-site-verification" content="SshyPvUWMt9RvlmGX7X4AJtFpVhrCiejltTVOqRbe6o" />
    <meta name="yandex-verification" content="85876cb37b3e3d2a" />
    <meta name="msvalidate.01" content="44E29CA719A9C2074B87ECF80ADDF5FD" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="www.onlinebooksreview.com">
    <meta property="og:image" content="" >
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Poppins:400,600,700%7CRoboto:400,400i,700' rel='stylesheet'>

    <!-- Css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/font-icons.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">

    <!-- Favicons -->
    <link rel="shortcut icon" href="/favicon.ico">
    {{--<link rel="apple-touch-icon" href="img/apple-touch-icon.png">--}}
    {{--<link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png">--}}
    {{--<link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png">--}}

    {{--TODO: add more favicon--}}

    @yield('run_custom_css_file')
    @yield('run_custom_css')
</head>

<body>

@yield('content')

<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
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

