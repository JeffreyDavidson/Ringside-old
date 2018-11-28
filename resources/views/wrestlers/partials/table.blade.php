<table class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <th>Name</th>
        <th>Height</th>
        <th>Weight</th>
        <th>Hometown</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @forelse($wrestlers as $wrestler)
            <tr>
                <td>{{ $wrestler->name }}</td>
                <td>{{ $wrestler->present()->height }}</td>
                <td>{{ $wrestler->weight }} lbs.</td>
                <td>{{ $wrestler->hometown }}</td>
                <td>
                    @include('partials.actions', ['resource' => 'wrestlers', 'model' => $wrestler, 'actions' => $actions, 'field' => 'wrestler_id'])
                </td>
            </tr>
        @empty
            <tr><td colspan="5">There are no records of this model.</td></tr>   
        @endforelse
    </tbody>
</table>
