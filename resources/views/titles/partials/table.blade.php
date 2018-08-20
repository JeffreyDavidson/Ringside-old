<table class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <th>ID</th>
        <th>Name</th>
        <th>Slug</th>
        <th>Introduced At</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @forelse($titles as $title)
            <tr>
                <td>{{ $title->id }}</td>
                <td>{{ $title->name }}</td>
                <td>{{ $title->slug }}</td>
                <td>{{ $title->present()->introduced_at }}</td>
                <td>
                    @include('partials.actions', ['resource' => 'titles', 'model' => $title, 'actions' => $actions, 'field' => 'title_id'])
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No Titles Of This Type</td></tr>
        @endforelse
    </tbody>
</table>