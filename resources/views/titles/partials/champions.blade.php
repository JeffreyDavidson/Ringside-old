<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
    <thead>
        <th>ID</th>
        <th>Champion</th>
        <th>Won On</th>
        <th>Lost On</th>
        <th>Length of Reign</th>
    </thead>
    <tbody>
        @foreach($title->champions as $champion)
            <tr>
                <td>{{ $champion->id }}</td>
                <td>{{ $champion->wrestler->name }}</td>
                <td>{{ $champion->present()->won_on }}</td>
                <td>{{ $champion->present()->lost_on }}</td>
                <td>{{ $champion->present()->length_of_reign }}</td>
            </tr>
        @endforeach
    </tbody>
</table>