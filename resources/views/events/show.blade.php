@extends('layouts.app')

@section('header')
    <h1 class="page-title">Event Details</h1>
@endsection

@section('content')
    <h1>{{ $event->name }}</h1>
    <p>{{ $event->formatted_date }}</p>
    <p>{{ $event->arena->name }}</p>
    @foreach($event->matches as $match)
        <div class="panel panel-bordered panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    @if ($loop->first)
                        Opening Match
                    @elseif ($loop->last)
                        Main Event
                    @else
                        Match #{{ $match->match_number }}
                    @endif
                </div>
            </div>
            <div class="panel-body">
                {{ $match->type->name }}
                <p>
                    @if ($match->titles)
                        @foreach ($match->titles as $title)
                            {{ $title->name }} Match
                        @endforeach
                    @endif
                </p>
                <p>
                    @if ($match->stipulations)
                        @foreach ($match->stipulations as $stipulation)
                            {{ $stipulation->name }} Match
                        @endforeach
                    @endif
                </p>
                <p>
                    @if ($match->referees)
                        @if ($match->referees->count() == 1)
                            Referee: {{  $match->referees->first()->full_name }}
                        @elseif ($match->referees->count() == 2)
                            Referees: {{ $match->referees->implode('full_name', ' & ') }}
                        @else
                            Referees: {{ $match->referees->implode('full_name', ', ') }}
                        @endif
                    @endif
                </p>
                <p>
                    @if ($match->titles)

                    @endif
                    {{ $match->wrestlers->implode('name', ' vs. ') }}
                </p>
                <p>{{ $match->preview }}</p>
            </div>
        </div>
    @endforeach
@endsection
