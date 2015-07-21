@extends('_layouts/back')

@section('body')
<section class="section">
    <div class="container">
        <h1 class="section__title">Your Organisations</h1>

        <table class="table">
            <thead class="table__head">
                <th class="table__head-cell">Organisation</th>
                <th class="table__head-cell" width="25%">Repositories</th>
            </thead>
            <tbody class="table__body">
            @foreach ($organisations as $organisation)
                <tr class="table__row">
                    <td class="table__cell  has-link"><a href="{{ route('get::back.organisation', $organisation) }}" class="table__link">{{ $organisation->name }}</a></td>
                    <td class="table__cell  color-success">{{ $organisation->repositories()->count() }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot class="table__foot">
                <tr class="table__row">
                    <td class="table__foot-cell  u-text-center" colspan="2">
                        Can't see an organisation?
                        <a href="{{ route('get::back.sync') }}" class="u-ml-  btn  btn--small  btn--success">Resync</a>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</section>

<section class="section">
    <div class="container">
        <h1 class="section__title">Your Repositories</h1>

        @include('_partials.repository.list', compact('repositories'))
    </div>
</section>
@stop
