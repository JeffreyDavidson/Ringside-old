@extends('layouts.app')

@section('header')
    <h1 class="page-title">Events</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-calendar"></i>List of Events</h3>
            @can('create', App\Models\Event::class)
                <div class="panel-actions">
                    <a class="btn btn-default pull-right" href="{{ route('events.create') }}">Create Event</a>
                </div>
            @endcan
        </div>
        <div class="panel-body container-fluid">
            <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Date</th>
                    <th>Arena</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                    @foreach($events as $event)
                        <tr>
                            <td>{{ $event->id }}</td>
                            <td>{{ $event->name }}</td>
                            <td>{{ $event->slug }}</td>
                            <td>{{ $event->present()->date }}</td>
                            <td>{{ $event->venue->name }}</td>
                            <td>
                                @include('partials.actions', ['resource' => 'events', 'model' => $event])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
