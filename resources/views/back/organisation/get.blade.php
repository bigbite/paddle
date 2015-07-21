@extends('_layouts/back')

@section('body')
<section class="section">
    <div class="container">
        <h1 class="section__title">{{ $organisation->name }}'s Repositories</h1>

        @include('_partials.repository.list', ['repositories' => $repositories, 'sync' => route('get::back.organisation.sync', $organisation)])
    </div>
</section>
@stop
