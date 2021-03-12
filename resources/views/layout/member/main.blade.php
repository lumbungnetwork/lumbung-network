<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Lumbung Network">
    <meta name="author" content="WeTheWolf">
    <link rel="shortcut icon" href="/image/icon2.png">
    <title>Lumbung Network</title>
    <link href="{{ asset('asset_member/css/main_all.css') }}" rel="stylesheet" type="text/css" />
    @yield('styles')
    <script src="{{ asset('asset_member/js/modernizr.min.js') }}"></script>

</head>

<body class="fixed-left">
    <div id="wrapper">
        @yield('content')
    </div>
    <script>
        var resizefunc = [];
    </script>
    <script src="{{ asset('asset_member/js/jquery.min.js') }}"></script>
    <script src="{{ asset('asset_member/js/tether.min.js') }}"></script><!-- Tether for Bootstrap -->
    <script src="{{ asset('asset_member/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('asset_member/js/detect.js') }}"></script>
    <script src="{{ asset('asset_member/js/fastclick.js') }}"></script>
    <script src="{{ asset('asset_member/js/jquery.blockUI.js') }}"></script>
    <script src="{{ asset('asset_member/js/waves.js') }}"></script>
    <script src="{{ asset('asset_member/js/jquery.nicescroll.js') }}"></script>
    <script src="{{ asset('asset_member/js/jquery.scrollTo.min.js') }}"></script>
    <script src="{{ asset('asset_member/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('asset_member/plugins/switchery/switchery.min.js') }}"></script>
    <script src="{{ asset('asset_member/js/jquery.core.js') }}"></script>
    <script src="{{ asset('asset_member/js/jquery.app.js') }}"></script>
    @yield('javascript')
</body>

</html>