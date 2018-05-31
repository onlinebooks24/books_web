<!Doctype html>
<html class="no-js" lang="">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title>Dashboard || OnlineBooksReview Web</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">
  <link rel="shortcut icon" href="/favicon.ico">
  <link rel="stylesheet" type="text/css" href="{{ asset('asset_admin/css/bootstrap.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('asset_admin/css/style.css') }}">
    <script type="text/javascript" src="{{ asset('asset_admin/js/jquery-2.1.4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('asset_admin/js/bootstrap.min.js')}}"></script>
    @yield('run_emergency_js')
    @yield('run_custom_css_file')
    @yield('run_custom_css')
</head>
	<body>	

    <div class="container">
      <div class="row">
        @include('includes.admin_header')
      </div>

      @yield('content')
    </div>


    @yield('run_custom_js_file')
    @yield('run_custom_jquery')
	</body>
</html>
