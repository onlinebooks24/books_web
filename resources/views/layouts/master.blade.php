<!Doctype html>
<html class="no-js" lang="en-US">

<head>
  <meta charset="utf-8">
  <title>@yield('title')</title>
  <meta http-equiv="Content-Type" content="text/html">
  <meta name="description" content="You will get reviews and recommendation of best online books.">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta property="og:locale" content="en_US" />
  <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.ico') }}"/>
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="www.onlinebooksreview.com" />
  <meta property="og:image" content="" />
  <link rel="shortcut icon" href="/favicon.ico">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    @yield('run_custom_css_file')
    @yield('run_custom_css')
</head>
	<body>	

    @yield('content')

    <script type="text/javascript" src="{{ asset('js/jquery-2.1.4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js')}}"></script>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-97703152-1', 'auto');
        ga('send', 'pageview');

    </script>

    @yield('run_custom_js_file')
    @yield('run_custom_jquery')
	</body>
</html>
