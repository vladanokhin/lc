<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <title>@yield('title')</title>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal mt-12">
    @include('app.parts.header')
    <div class="flex flex-col md:flex-row">
        @yield('content')
    </div>
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
</body>
</html>
