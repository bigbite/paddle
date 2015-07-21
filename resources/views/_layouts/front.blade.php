<!DOCTYPE html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge, chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    @include('_partials.favicons')

    <title>Paddle - Herbert</title>

    <link rel="stylesheet" href="/assets/styles/app.css">
</head>
<body>
    @include('_partials.header')

    <main class="main" role="main">@yield('body')</main>

    @include('_partials.footer')

    <script src="/assets/scripts/vendor.js"></script>
    <script src="/assets/scripts/bundle.js"></script>
</body>
</html>
