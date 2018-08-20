<table class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <th>ID</th>
        <th>Name</th>
        <th>Height</th>
        <th>Weight</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($wrestlers as $wrestler)
            <tr>
                <td>{{ $wrestler->id }}</td>
                <td>{{ $wrestler->name }}</td>
                <td>{{ $wrestler->present()->height }}</td>
                <td>{{ $wrestler->weight }} lbs.</td>
                <td>
                    @include('partials.actions', ['resource' => 'wrestlers', 'model' => $wrestler, 'actions' => collect(['edit', 'show', 'delete', 'retire']), 'field' => 'wrestler_id'])
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
