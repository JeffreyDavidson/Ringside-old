<table class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <th>Name</th>
        <th>Slug</th>
        <th>Date</th>
        <th>Arena</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @forelse($events as $event)
            <tr>
                <td>{{ $event->name }}</td>
                <td>{{ $event->slug }}</td>
                <td>{{ $event->present()->date }}</td>
                <td>{{ $event->venue->name }}</td>
                <td>
                    @include('partials.actions', ['resource' => 'events', 'model' => $event, 'actions' => $actions, 'field_id' => 'event_id'])
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No Events Of This Type</td></tr>
        @endforelse
    </tbody>
</table>