<table class="table">
    <thead class="table__head">
        <th class="table__head-cell">Repository</th>
        <th class="table__head-cell" width="25%">Deploy</th>
    </thead>
    <tbody class="table__body">
    @foreach ($repositories as $repository)
        <tr class="table__row">
            <td class="table__cell  has-link"><a href="{{ route('get::back.repository', $repository) }}" class="table__link">{{ $repository->display_name }}</a></td>
            <td class="table__cell  color-success">@if ($repository->rigged) <b class="ico-success  ico--lonely"></b> @endif</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot class="table__foot">
        <tr class="table__row">
            <td class="table__foot-cell  u-text-center" colspan="2">
                Can't see a repo?
                <a href="{{ isset($sync) ? $sync : route('get::back.sync') }}" class="u-ml-  btn  btn--small  btn--success">Resync</a>
            </td>
        </tr>
    </tfoot>
</table>
