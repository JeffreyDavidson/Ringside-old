<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
    <thead>
        <th>ID</th>
        <th>Event</th>
        <th>Match</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @forelse($title->matches as $match)
            <tr>
                <td>{{ $match->id }}</td>
                <td>{{ $match->event->name }}</td>
                <td>{{ $match->present()->wrestlers }}</td>
                <td>
                    <a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route('events.show', $match->event->id) }}" data-toggle="tooltip" data-original-title="Show">
                        <i class="icon wb-eye" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">This title has not been competed for in a match.</td></tr>    
        @endforelse
    </tbody>
</table>