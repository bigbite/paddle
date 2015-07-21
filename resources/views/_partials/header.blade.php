<header class="page-head">
    <div class="container">
        <div class="page-head__logo-wrapper">
            <a class="page-head__logo-link" href="{{ url('/') }}" alt="Homepage"></a>
            <object type="image/svg+xml" data="/assets/images/logo-secondary.svg" class="page-head__logo">
                <img src="/assets/images/logo-secondary.png" alt="">
            </object>
        </div>

        <nav class="page-nav">
            <ul class="page-nav__list">
            @if (auth()->check())
                <li class="page-nav__item"><a href="{{ route('get::back') }}" class="page-nav__link">Repositories</a></li>
                <li class="page-nav__item"><a href="{{ route('get::oauth.out') }}" class="page-nav__link  btn  btn--small  btn--light">Sign out</a></li>
            @else
                <li class="page-nav__item"><a href="{{ route('get::oauth.redirect') }}" class="page-nav__link  btn  btn--small"><b class="ico-github"></b>Sign in with GitHub</a></li>
            @endif
            </ul>
        </nav>
    </div>
</header>

<div class="container" id="flash-messages">
@foreach (session('__flash_messages.error', []) as $message)
    <div class="flash-message  flash-message--error" data-dismissable="click"><b class="ico-error"></b>{{ $message }}</div>
@endforeach

@foreach (session('__flash_messages.warning', []) as $message)
    <div class="flash-message  flash-message--warning" data-dismissable="click"><b class="ico-warning"></b>{{ $message }}</div>
@endforeach

@foreach (session('__flash_messages.success', []) as $message)
    <div class="flash-message  flash-message--success" data-dismissable="click"><b class="ico-success"></b>{{ $message }}</div>
@endforeach
</div>
