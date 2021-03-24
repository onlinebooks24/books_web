<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>

    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="keywords" content="@yield('meta_keyword', 'best books, review, top books, best books review');" >
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="@yield('meta_description' , 'You will get reviews and recommendation of best online books. Get suggestion of buying best books from online.')">

    <meta property="og:locale" content="en_US" >
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.ico') }}">
    <meta name="google-site-verification" content="SshyPvUWMt9RvlmGX7X4AJtFpVhrCiejltTVOqRbe6o" />
    <meta name="yandex-verification" content="85876cb37b3e3d2a" />
    <meta name="ahrefs-site-verification" content="eed9f5a994afba5bff1e77a9dcc30e14f2bfb6196446390915b9614a5368bfdd">
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

@if (env('APP_ENV') == 'production' && empty(Auth::user()))
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-97703152-1', 'auto');
        ga('send', 'pageview');
    </script>

    <!-- Hotjar Tracking Code for www.onlinebooksreview.com -->
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:2314669,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>
@endif

@yield('run_custom_js_file')
@yield('run_custom_jquery')
</body>
</html>

