@extends('layouts.app')

@section('content')
    <h1>{{ $arena->name }}</h1>
    <p>{{ $arena->address }}</p>
    <p>{{ $arena->city }}, {{ $arena->state }} {{ $arena->postcode }}</p>

    @if ($arena->events->count() > 0)
        <div class="panel panel-bordered panel-primary">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-building"></i>Events Held</h3>
            </div>
            <div class="panel-body container-fluid">
                <table class="table table-bordered">
                    <thead>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Date</th>
                    </thead>
                    <tbody>
                    @foreach($arena->events as $event)
                        <tr>
                            <td>{{ $event->name }}</td>
                            <td>{{ $event->slug }}</td>
                            <td>{{ $event->formatted_date }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
