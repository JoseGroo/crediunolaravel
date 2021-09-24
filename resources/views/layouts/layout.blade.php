
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Crediuno | @yield("title")</title>


    <!--Favicon-->
    <!--<link rel="icon" type="image/png" sizes="16x16" href="@Url.Content("~/Public/images/favicon/favicon-16x16.png")">-->
    <!--<link rel="manifest" href="@Url.Content("~/Public/images/favicon/manifest.json")">-->
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
<body class="">
    <div id="wrapper">
        @include("layouts._menu")
        <div id="page-wrapper" class="gray-bg">

            @include('layouts._header')
            @include('layouts._title')

            <div class="wrapper wrapper-content animated fadeInRigh m-t-md">
                @include('general._messages')
                @yield('content')
            </div>
        </div>
    </div>


    @include('layouts._loader')
</body>
</html>