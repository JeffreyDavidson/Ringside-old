<table class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <th>ID</th>
        <th>Name</th>
        <th>Slug</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($stipulations as $stipulation)
            <tr>
                <td>{{ $stipulation->id }}</td>
                <td>{{ $stipulation->name }}</td>
                <td>{{ $stipulation->slug }}</td>
                <td>
                    @include('partials.actions', ['resource' => 'stipulations', 'model' => $stipulation, 'actions' => collect(['edit', 'show', 'delete'])])
                </td>
            </tr>
        @endforeach
    </tbody>
</table>