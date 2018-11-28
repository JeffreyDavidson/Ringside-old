<table class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <th>Name</th>
        <th>Slug</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @forelse($stipulations as $stipulation)
            <tr>
                <td>{{ $stipulation->name }}</td>
                <td>{{ $stipulation->slug }}</td>
                <td>
                    @include('partials.actions', ['resource' => 'stipulations', 'model' => $stipulation, 'actions' => collect(['edit', 'view', 'delete'])])
                </td>
            </tr>
        @empty 
            <tr><td colspan="3">There are no records of this model.</td></tr>    
        @endforelse
    </tbody>
</table>