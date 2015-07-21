@extends('_layouts/front')

@section('body')
<section class="section center-align">
    <div class="container">
        <a class="btn offset-s3 s6" href="{{ route('get::oauth.redirect') }}"><i class="ico-github-circled left"></i>Sign in with GitHub</a>
    </div>
</section> 
@stop
