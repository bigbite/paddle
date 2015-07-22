@extends('_layouts/back')

@section('body')
<section class="section">
    <div class="container">
        <h1 class="section__title">{{ $repository->display_name }}</h1>
        <p class="section__description">Hook to GitHub for automatic shipping of commits</p>

    @if ($repository->hooked)
        <a href="{{ route('get::back.repository.unhook', $repository) }}" class="btn  btn--small  btn--danger"><b class="ico-github"></b>Unhook from GitHub</a>
    @else
        <a href="{{ route('get::back.repository.hook', $repository) }}" class="btn  btn--small"><b class="ico-github"></b>Hook to GitHub</a>
    @endif
    </div>
</section>

<hr class="break" />

<section class="section">
    <div class="container">
        <p class="section__title">Manual Release</p>
        <p class="section__description">If you already have a commit ready, you can manually deploy it below</p>

        <form action="{{ route('get::back.repository.release', compact('repository')) }}" method="get">
            <!-- <div class="release-group"> -->
                {!! csrf_field() !!}

                <div class="input__row">
                    <div class="input__column">
                        <input placeholder="latest" type="text" name="release" id="release">
                    </div>
                    <div class="input__column">
                        <button class="btn  btn--success  btn--small" type="submit">Release</button>
                    </div>
                </div>
            <!-- </div> -->
        </form>
    </div>
</section>

<hr class="break" />

<section class="section">
    <div class="container">
        <h1 class="section__title">Details</h1>
        <p class="section__description">Add your SVN details so Paddle can sync {{ $repository->getRouteKey() }} to the SVN<br><span class="muted">note: if you're using SSH authentication, leave the password field blank</span></p>

        <form action="{{ route('post::back.repository', $repository) }}" method="post">
            {!! csrf_field() !!}

            <div class="input__row{{ $errors->has('email') ? '  has-error' : '' }}">
                <label for="email">Email</label>
                <input type="email" name="email" id="email"
                       value="{{ old('email', $repository->email) }}">
            @if ($errors->has('email'))
                <span class="error-message">{{ $errors->first('email') }}</span>
            @endif
            </div>

            <div class="input__row">
                <div class="input__column{{ $errors->has('username') ? '  has-error' : '' }}">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username"
                           value="{{ old('username', $repository->username) }}"
                           placeholder="{{ $repository->vendor }}">
                @if ($errors->has('username'))
                    <span class="error-message">{{ $errors->first('username') }}</span>
                @endif
                </div>
                <div class="input__column{{ $errors->has('password') ? '  has-error' : '' }}">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="{{ $repository->password ? 'hidden' : '' }}">
                @if ($errors->has('password'))
                    <span class="error-message">{{ $errors->first('password') }}</span>
                @endif
                </div>
            </div>

            <div class="input__row{{ $errors->has('svn') ? '  has-error' : '' }}">
                <label for="svn">SVN Address</label>
                <input type="url" name="svn" id="svn"
                       value="{{ old('svn', $repository->svn) }}">
                @if ($errors->has('svn'))
                    <span class="error-message">{{ $errors->first('svn') }}</span>
                @endif
            </div>

            <div class="input__row">
                <div class="input__column  u-text-left">
                    <a href="{{ route('get::back') }}" class="btn  btn--small  btn--light">Cancel</a>
                </div>
                <div class="input__column  u-text-right">
                    <input class="btn  btn--small  btn--success" type="submit" value="Save details">
                </div>
            </div>
        </form>
    </div>
</section>
@stop
