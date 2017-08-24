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
                    {{ $match->present()->match_number($loop) }}
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
                @if ($match->stipulations->count() > 0)
                    <p>{{ $match->present()->stipulations }} Match</p>
                @endif
                <p>
                    Referee(s): {{ $match->present()->referees }}
                </p>
                <p>
                    {{ $match->present()->wrestlers }}
                </p>
                <p>{{ $match->preview }}</p>
            </div>
        </div>
    @endforeach
@endsection
