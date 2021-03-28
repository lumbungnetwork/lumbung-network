<!doctype html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{$title ?? ''}}</title>
    <link rel="shortcut icon" href="{{Config::get('services.app.protocol_url')}}/image/icon2.png">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('style')
    <style>
        .swal2-popup {
            font-size: 0.8rem !important;
            border-radius: 25px !important;
            background: linear-gradient(145deg, #FFFFFF, #CCD4DD) !important;
            box-shadow: 0.2em 0.2em calc(0.2em * 2) #AEBCC9, calc(0.2em * -1) calc(0.2em * -1) calc(0.2em * 2) #d3d3d3 !important;
        }
    </style>
</head>

<body class="bg-gray-100">
    @include('sweetalert::alert')
    @yield('content')

    <script src="{{Config::get('services.app.protocol_url')}}/asset_member/js/jquery.min.js"></script>
    <script src="{{Config::get('services.app.protocol_url')}}/vendor/sweetalert/sweetalert.all.js"></script>
    @yield('scripts')
</body>
@yield('footer')