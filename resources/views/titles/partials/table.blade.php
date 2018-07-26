<table class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <th>ID</th>
        <th>Name</th>
        <th>Slug</th>
        <th>Introduced At</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($titles as $title)
            <tr>
                <td>{{ $title->id }}</td>
                <td>{{ $title->name }}</td>
                <td>{{ $title->slug }}</td>
                <td>{{ $title->present()->introduced_at }}</td>
                <td>
                    @include('partials.actions', ['resource' => 'titles', 'model' => $title, 'actions' => collect(['edit', 'show', 'delete', 'retire'])])
                </td>
            </tr>
        @endforeach
    </tbody>
</table>