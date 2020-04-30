<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Crediuno | @yield('title')</title>

    <meta name="msapplication-TileColor" content="#ffffff">
    <!--<meta name="msapplication-TileImage" content="@Url.Content("~/Public/images/favicon/ms-icon-144x144.png")">-->
    <meta name="theme-color" content="#ffffff">

    <link rel="icon" href="{{ asset('images/favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800,900&display=swap" rel="stylesheet">
    <!-- Styles -->
    @section("styles")
        @include("layouts._styles")
    @show

    @section("scripts")
        @include("layouts._scripts")
    @show
</head>
<body class="bg-light position-relative">

    <div class="position-relative w-70 h-100 full-height m-auto">
        @yield('content')
    </div>

@include('layouts._loader')
</body>
</html>