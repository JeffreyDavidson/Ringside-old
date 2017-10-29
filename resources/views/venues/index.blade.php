@extends('layouts.app')

@section('header')
    <h1 class="page-title">Venues</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-building"></i>List of Venues</h3>
            @can('create', App\Models\Venue::class)
                <div class="panel-actions">
                    <a class="btn btn-default pull-right" href="{{ route('venues.create') }}">Create Venue</a>
                </div>
            @endcan
        </div>
        <div class="panel-body container-fluid">
            <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
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
                            @can('edit', $venue)
                                <a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route('venues.edit', $venue) }}" data-toggle="tooltip" data-original-title="Edit">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </a>
                            @endcan
                            @can('show', $venue)
                                <a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route('venues.show', $venue) }}" data-toggle="tooltip" data-original-title="Show">
                                    <i class="icon wb-eye" aria-hidden="true"></i>
                                </a>
                            @endcan
                            @can('delete', $venue)
                                <form style="display: inline-block;" action="{{ route('venues.destroy', $venue) }}" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button style="cursor: pointer" class="btn btn-sm btn-icon btn-flat btn-default" type="submit" data-toggle="tooltip" data-original-title="Delete">
                                        <i class="icon wb-close" aria-hidden="true"></i>
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
