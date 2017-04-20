@extends('layouts.app')

@section('content')
    <h1>{{ $stipulation->name }}</h1>

    @if($stipulation->matches->count() > 0)
        <h2>Matches</h2>
        <table class="table table-bordered">
            <thead>
                <th>Event</th>
            </thead>
            <tbody>
            @foreach($stipulation->matches as $match)
                <tr>
                    <td>{{ $match->event->name }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endsection
