@extends('layouts.app')

@section('header')
    <h1 class="page-title">Wrestlers</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-group"></i>List of Wrestlers</h3>
            <div class="panel-actions">
                <div class="dropdown">
                    <button id="wrestlerFilters" class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true" >
                        Filters
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="wrestlerFilters" role="menu">
                        <li role="presentation"><a href="javascript:void(0)" role="menuitem">View All Wrestlers</a></li>
                        <li role="presentation"><a href="javascript:void(0)" role="menuitem">View Active Wrestlers</a></li>
                        <li role="presentation"><a href="javascript:void(0)" role="menuitem">View Inactive Wrestlers</a></li>
                        <li role="presentation"><a href="javascript:void(0)" role="menuitem">View Injured Wrestlers</a></li>
                        <li role="presentation"><a href="javascript:void(0)" role="menuitem">View Suspended Wrestlers</a></li>
                        <li role="presentation"><a href="javascript:void(0)" role="menuitem">View Retired Wrestlers</a></li>
                    </ul>
                </div>
                <a class="btn btn-default pull-right" href="{{ route('wrestlers.create') }}">Create Wrestler</a>
            </div>
        </div>
        <div class="panel-body container-fluid">
            <table id="wrestlersList" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                @foreach($wrestlers as $wrestler)
                    <tr>
                        <td>{{ $wrestler->id }}</td>
                        <td>{{ $wrestler->name }}</td>
                        <td>{{ $wrestler->slug }}</td>
                        <td>
                            <a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route('wrestlers.edit', ['id' => $wrestler->id]) }}" data-toggle="tooltip" data-original-title="Edit">
                                <i class="icon wb-wrench" aria-hidden="true"></i>
                            </a>
                            <a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route('wrestlers.show', ['id' => $wrestler->id]) }}" data-toggle="tooltip" data-original-title="Show">
                                <i class="icon wb-eye" aria-hidden="true"></i>
                            </a>
                            <button class="btn btn-sm btn-icon btn-flat btn-default" type="button" data-toggle="tooltip" data-original-title="Delete">
                                <i class="icon wb-close" aria-hidden="true"></i>
                            </button>
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
            $('#wrestlersList').DataTable({
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