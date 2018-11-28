<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
    <thead>
        <th>Champion</th>
        <th>Won On</th>
        <th>Lost On</th>
        <th>Length of Reign</th>
    </thead>
    <tbody>
        @forelse($title->champions as $champion)
            <tr>
                <td>{{ $champion->wrestler->name }}</td>
                <td>{{ $champion->present()->won_on }}</td>
                <td>{{ $champion->present()->lost_on }}</td>
                <td>{{ $champion->present()->length_of_reign }}</td>
            </tr>
        @empty
            <tr><td colspan="4">This title does not have any past champions.</td></tr>    
        @endforelse
    </tbody>
</table>