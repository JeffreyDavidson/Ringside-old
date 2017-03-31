@extends('layouts.app')

@section('content')
    <h1>{{ $wrestler->name }}</h1>
    <p>Hometown: {{ $wrestler->bio->hometown }}</p>
    <p>Height: {{ $wrestler->bio->formatted_height }}</p>
    <p>Weight: {{ $wrestler->bio->weight }} lbs.</p>

    @if($wrestler->bio->signature_move)
        <p>{{ $wrestler->bio->signature_move }}</p>
    @endif

    @if($wrestler->currentManagers->count() > 0)
        <p>Current Managers:</p>
        @foreach($wrestler->currentManagers as $manager)
            {{ $manager->name }}
        @endforeach
    @endif

    @if($wrestler->currentManagers->count() > 0)
        <p>Previous Managers</p>
        @foreach($wrestler->previousManagers as $manager)
            {{ $manager->name }}
        @endforeach
    @endif

    @if($wrestler->titles->count() > 0)
        <p>Titles Held</p>
        @foreach($wrestler->titles->groupByTitle() as $title)
            {{ $title[0]->title->name }} {{ '(' . $title->count(). 'x)'}}
        @endforeach
    @endif

    @if($wrestler->injuries->count() > 0)
        <p>Previous Injuries</p>
        @foreach($wrestler->injuries as $injury)
            {{ $injury->injured_at->format('F m, Y') }} to {{ $injury->healed_at->format('F m, Y') }}
        @endforeach
    @endif

    @if($wrestler->matches->count() > 0)
        <p>Matches</p>
        <table>
            <thead>
                <th>Event</th>
            </thead>
            <tbody>
                @foreach($wrestler->matches as $match)
                    <tr>{{ $match->event->name }}</tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
