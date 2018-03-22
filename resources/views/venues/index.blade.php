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
                                @include('partials.actions', ['resource' => 'venues', 'model' => $venue])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
