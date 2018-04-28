<table class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <th>ID</th>
        <th>Name</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Postcode</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($venues as $venue)
            <tr>
                <td>{{ $venue->id }}</td>
                <td>{{ $venue->name }}</td>
                <td>{{ $venue->address }}</td>
                <td>{{ $venue->city }}</td>
                <td>{{ $venue->state }}</td>
                <td>{{ $venue->postcode }}</td>
                <td>
                    @include('partials.actions', ['resource' => 'venues', 'model' => $venue])
                </td>
            </tr>
        @endforeach
    </tbody>
</table>