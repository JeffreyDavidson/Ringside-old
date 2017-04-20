@extends('layouts.app')

@section('content')
    <h1>{{ $arena->name }}</h1>
    <p>{{ $arena->address }}</p>
    <p>{{ $arena->city }}, {{ $arena->state }} {{ $arena->postcode }}</p>

    @if($arena->events()->count() > 0)
        <h2>Events</h2>
        <table class="table table-bordered">
            <thead>
                <th>Event</th>
            </thead>
            <tbody>
                @foreach($arena->event as $event)
                    <tr>
                        <td>{{ $event->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
