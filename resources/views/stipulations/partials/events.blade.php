<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
    <thead>
        <th>Event</th>
        <th>Match</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @forelse($stipulation->pastMatches() as $match)
            <tr>
                <td>{{ $match->event->name }}</td>
                <td>{{ $match->present()->wrestlers }}</td>
                <td>
                    <a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route('events.show', $match->event->id) }}" data-toggle="tooltip" data-original-title="Show">
                        <i class="icon wb-eye" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr><td colspan="3">There are no records of this model.</td></tr>    
        @endforelse
    </tbody>
</table>