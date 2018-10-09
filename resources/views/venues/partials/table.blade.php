<table class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <th>Name</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Postcode</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @forelse($venues as $venue)
            <tr>
                <td>{{ $venue->name }}</td>
                <td>{{ $venue->address }}</td>
                <td>{{ $venue->city }}</td>
                <td>{{ $venue->state }}</td>
                <td>{{ $venue->postcode }}</td>
                <td>
                    @include('partials.actions', ['resource' => 'venues', 'model' => $venue, 'actions' => collect(['edit', 'view', 'delete'])])
                </td>
            </tr>
        @empty 
            <tr><td colspan="6">There are no records of this model.</td></tr>    
        @endforelse
    </tbody>
</table>