<!doctype html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>{{$title ?? ''}}</title>
    <meta name="description"
        content="Lumbung Finance is a service, providing easier way to generate gains from Decentralized Finance (DeFi) protocols." />
    <meta name="robots" content="index, follow" />
    <meta name="referrer" content="always" />

    <!-- Social & Open Graph -->
    <meta property="og:title" content="Lumbung Finance" />
    <meta property="og:description"
        content="Lumbung Finance is a service, providing easier way to generate gains from Decentralized Finance (DeFi) protocols." />
    <meta property="og:image" content="https://lumbung.network/image/lumbung-finance-social.png">

    <meta property="og:url" content="https://finance.lumbung.network" />
    <meta name="twitter:title" content="Lumbung Finance">
    <meta name="twitter:description"
        content="Lumbung Finance is a service, providing easier way to generate gains from Decentralized Finance (DeFi) protocols." />
    <meta name="twitter:image" content="https://lumbung.network/image/lumbung-finance-social.png" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@lumbungnetwork" />
    <meta name="twitter:creator" content="@evanstinger" />

    <link rel="shortcut icon" type="image/png" href="{{Config::get('services.app.protocol_url')}}/image/icon2.png">
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
    <script src="{{Config::get('services.app.protocol_url')}}/vendor/sweetalert/sweetalert2.all.min.js"></script>
    @yield('scripts')
</body>
@yield('footer')