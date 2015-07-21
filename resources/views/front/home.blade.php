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
<div class="container" id="flash-messages">
@foreach (session('__flash_messages.error', []) as $message)
    <div class="flash-message  flash-message--error" data-dismissable="click"><b class="ico-error"></b>{{ $message }}</div>
@endforeach

@foreach (session('__flash_messages.warning', []) as $message)
    <div class="flash-message  flash-message--warning" data-dismissable="click"><b class="ico-warning"></b>{{ $message }}</div>
@endforeach

    <div class="page-hero">
       <div class="container">
            <div class="page-hero__logo-wrapper">
                <object type="image/svg+xml" data="/assets/images/logo.svg" class="page-hero__logo">
                    <img src="/assets/images/logo.png" alt="">
                </object>
            </div>

            <h1 class="page-hero__title">Tag a release on GitHub and have it automatically shipped to the SVN</h1>

            <a href="{{ url('sign-in/redirect') }}" class="page-hero__cta  btn"><b class="ico-github"></b>Sign in with GitHub</a>
        </div>
    </div>

    @include('_partials.footer')

    <script src="/assets/scripts/vendor.js"></script>
    <script src="/assets/scripts/bundle.js"></script>
</body>
</html>
