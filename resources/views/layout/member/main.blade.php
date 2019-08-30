<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Safra">
        <meta name="author" content="safra IT">
        <link rel="shortcut icon" href="assets/images/favicon.ico">
        <title>Safra Member</title>
        <link href="{{ asset('asset_member/css/style.css') }}" rel="stylesheet" type="text/css" />
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