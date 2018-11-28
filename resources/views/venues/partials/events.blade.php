<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
    <thead>
        <th>Name</th>
        <th>Date</th>
        <th>Main Event</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @forelse($venue->pastEvents as $event)
            <tr>
                <td>{{ $event->name }}</td>
                <td>{{ $event->present()->date }}</td>
                <td>{{ $event->mainEvent->present()->wrestlers }}</td>
                <td>
                    <a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route('events.show', $event) }}" data-toggle="tooltip" data-original-title="Show">
                        <i class="icon wb-eye" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">There are no records of this model.</td></tr>    
        @endforelse
    </tbody>
</table>