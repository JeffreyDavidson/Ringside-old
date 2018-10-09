<table class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <th>Name</th>
        <th>Slug</th>
        <th>Introduced At</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @forelse($titles as $title)
            <tr>
                <td>{{ $title->name }}</td>
                <td>{{ $title->slug }}</td>
                <td>{{ $title->present()->introduced_at }}</td>
                <td>
                    @include('partials.actions', ['resource' => 'titles', 'model' => $title, 'actions' => $actions, 'field' => 'title_id'])
                </td>
            </tr>
        @empty
            <tr><td colspan="4">No titles of this type.</td></tr>
        @endforelse
    </tbody>
</table>