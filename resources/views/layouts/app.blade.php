<!doctype html>

<head>
    <!-- ... --->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{$title}}</title>
    <link rel="shortcut icon" href="/image/icon2.png">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('style')
</head>
@yield('body')
@yield('footer')
@yield('scripts')