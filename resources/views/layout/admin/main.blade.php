<!DOCTYPE html>
<html>
    <head>
        <title>Safra</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <link rel="shortcut icon" type="image/png" href="/images/favicon.jpeg"/>
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/paper-dashboard.css?v=2.0.1') }}">
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
        @yield('styles')
    </head>
    <body>
        <div class="wrapper ">
            @yield('content')
        </div>
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/popper.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/perfect-scrollbar.jquery.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap-notify.js') }}"></script>
        <script src="{{ asset('js/paper-dashboard.min.js?v=2.0.1') }}"></script>
        @yield('javascript')
    </body>
</html>