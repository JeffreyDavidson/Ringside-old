@extends('layouts.app')

@section('header')
    <h1 class="page-title">Arenas</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-building"></i>List of Arenas</h3>
            <div class="panel-actions">
                <a class="btn btn-default pull-right" href="{{ route('arenas.create') }}">Create Arena</a>
            </div>
        </div>
        <div class="panel-body container-fluid">
            <table id="arenasList" class="table table-striped table-bordered" cellspacing="0" width="100%">
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
                @foreach($arenas as $arena)
                    <tr>
                        <td>{{ $arena->id }}</td>
                        <td>{{ $arena->name }}</td>
                        <td>{{ $arena->address }}</td>
                        <td>{{ $arena->city }}</td>
                        <td>{{ $arena->state }}</td>
                        <td>{{ $arena->postcode }}</td>
                        <td>
                            <a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route('arenas.edit', ['id' => $arena->id]) }}" data-toggle="tooltip" data-original-title="Edit">
                                <i class="icon wb-wrench" aria-hidden="true"></i>
                            </a>
                            <a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route('arenas.show', ['id' => $arena->id]) }}" data-toggle="tooltip" data-original-title="Show">
                                <i class="icon wb-eye" aria-hidden="true"></i>
                            </a>
                            <form style="display: inline-block;" action="{{ route('arenas.destroy', $arena->id) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button style="cursor: pointer" class="btn btn-sm btn-icon btn-flat btn-default" type="submit" data-toggle="tooltip" data-original-title="Delete">
                                    <i class="icon wb-close" aria-hidden="true"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('footer-scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#arenasList').DataTable({
                "pagingType": "full_numbers",
                "columnDefs": [
                    { "width": "20px", "targets": 0 },
                    { "width": "150px", "targets": -1 },
                    { "targets": -1, "orderable": false }
                ]
            });
        } );
    </script>
@endsection