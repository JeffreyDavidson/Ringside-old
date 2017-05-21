@extends('layouts.app')

@section('header')
    <h1 class="page-title">{{ $arena->name }}</h1>
@endsection

@section('content')
    <p>{{ $arena->address }}</p>
    <p>{{ $arena->city }}, {{ $arena->state }} {{ $arena->postcode }}</p>
    <div class="panel panel-bordered">

    </div>
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-calendar"></i>List of Events</h3>
            <div class="panel-actions">
                <a class="btn btn-default pull-right" href="{{ route('arenas.index') }}">Back To All Arenas</a>
            </div>
        </div>
        <div class="panel-body container-fluid">
            <h2></h2>
            <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Main Event</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                @foreach($arena->events as $event)
                    <tr>
                        <td>{{ $event->id }}</td>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->formatted_date }}</td>
                        <td>{{ $event->matches->last()->formatted_wrestlers }}</td>
                        <td>
                            <a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route('events.show', $event->id) }}" data-toggle="tooltip" data-original-title="Show">
                                <i class="icon wb-eye" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
