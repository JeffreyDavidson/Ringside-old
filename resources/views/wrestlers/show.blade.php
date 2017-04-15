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
        <h2>Current Managers:</h2>
        @foreach($wrestler->currentManagers as $manager)
            {{ $manager->name }}
        @endforeach
    @endif

    @if($wrestler->currentManagers->count() > 0)
        <h2>Previous Managers</h2>
        @foreach($wrestler->previousManagers as $manager)
            {{ $manager->name }}
        @endforeach
    @endif

    @if($wrestler->titles->count() > 0)
        <h2>Titles Held</h2>
        @foreach($wrestler->titles->groupByTitle() as $titles)
            {{ "{$titles->first()->title->name} ({$titles->count()}x)" }}
        @endforeach
    @endif

    @if($wrestler->injuries->count() > 0)
        <h2>Previous Injuries</h2>
        @foreach($wrestler->injuries as $injury)
            {{ $injury->injured_at->format('F m, Y') }} to {{ $injury->healed_at->format('F m, Y') }}
        @endforeach
    @endif

    @if($wrestler->matches->count() > 0)
        <h2>Matches</h2>
        <table class="table table-bordered">
            <thead>
                <th>Event</th>
            </thead>
            <tbody>
                @foreach($wrestler->matches as $match)
                    <tr>
                        <td>{{ $match->event->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
