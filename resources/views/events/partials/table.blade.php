<table class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <th>ID</th>
        <th>Name</th>
        <th>Slug</th>
        <th>Date</th>
        <th>Arena</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($events as $event)
            <tr>
                <td>{{ $event->id }}</td>
                <td>{{ $event->name }}</td>
                <td>{{ $event->slug }}</td>
                <td>{{ $event->present()->date }}</td>
                <td>{{ $event->venue->name }}</td>
                <td>
                    @include('partials.actions', ['resource' => 'events', 'model' => $event, 'actions' => collect(['edit', 'show', 'delete', 'archive'])])
                </td>
            </tr>
        @endforeach
    </tbody>
</table>