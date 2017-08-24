@extends('layouts.app')

@section('header')
    <h1 class="page-title">{{ $event->name }}</h1>
    <p>{{ $event->present()->date }}</p>
    <p>{{ $event->venue->name }}</p>
@endsection

@section('content')
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
                    Referee(s): {{ $match->present()->referees }}
                </p>
                <p>
                    @if ($match->titles)

                    @endif
                </p>
                <p>
                    {{ $match->present()->wrestlers }}
                </p>
                <p>{{ $match->preview }}</p>
            </div>
        </div>
    @endforeach
@endsection
