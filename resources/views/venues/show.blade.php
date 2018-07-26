@extends('layouts.app')

@section('header')
    <h1 class="page-title">Show Venue</h1>
@endsection

@section('content')
    <div class="panel panel-primary panel-bordered">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-info-circle"></i>Basic Info</h3>
            <div class="panel-actions">
                <a class="btn btn-default pull-right" href="{{ route('venues.index') }}">Back To All Venues</a>
            </div>
        </div>
        <div class="panel-body">
            <p>Venue Name: {{ $venue->name }}</p>
            <p>Address:</p>
            <p>{{ $venue->address }}<br>{{ $venue->city }}, {{ $venue->state }} {{ $venue->postcode }}</p>
        </div>
    </div>

    @if ($venue->hasPastEvents())
        <div class="panel panel-bordered panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="icon fa-calendar"></i>List of Previous Events</h3>
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
                        {{-- @foreach($venue->pastEvents as $event)
                            <tr>
                                <td>{{ $event->id }}</td>
                                <td>{{ $event->name }}</td>
                                <td>{{ $event->present()->date }}</td>
                                <td>{{ $event->mainEvent->present()->wrestlers }}</td>
                                <td>
                                    <a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route('events.show', $event) }}" data-toggle="tooltip" data-original-title="Show">
                                        <i class="icon wb-eye" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
