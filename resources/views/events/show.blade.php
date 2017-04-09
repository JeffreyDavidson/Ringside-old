@extends('layouts.app')

@section('header')
    <h1 class="page-title">Event Details</h1>
@endsection

@section('content')
    <h1>{{ $event->name }}</h1>
    <p>{{ $event->formatted_date }}</p>
    <p>{{ $event->arena->name }}</p>
    @foreach($event->matches as $match)
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title">
                    @if ($loop->first)
                        Opening Match
                    @elseif ($loop->last)
                        Main Event
                    @else
                        Match #{{ $match->match_number }}
                    @endif
                    <div class="panel-desc">
                        {{ $match->type->name }}
                        @if ($match->titles)
                            @foreach ($match->titles as $title)
                                {{ $title->name }} Match
                            @endforeach
                        @endif
                        @if ($match->stipulations)
                            @foreach ($match->stipulations as $stipulation)
                                {{ $stipulation->name }} Match
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <p>{{ $match->preview }}</p>
            </div>
        </div>
    @endforeach
@endsection
