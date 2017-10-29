@extends('layouts.app')

@section('header')
    <h1 class="page-title">{{ $wrestler->name }}</h1>
@endsection

@section('content')
    <p>Hometown: {{ $wrestler->hometown }}</p>
    <p>Height: {!!  $wrestler->present()->height !!}</p>
    <p>Weight: {{ $wrestler->weight }} lbs.</p>

    @if($wrestler->signature_move)
        <p>Signature Move: {{ $wrestler->signature_move }}</p>
    @endif

    @if($wrestler->isCurrentlyAChampion())
        <h2>Current Titles</h2>
        <ul>
            @foreach($wrestler->currentTitlesHeld as $champion)
                <li>{{ $champion->title->name }}</li>
            @endforeach
        </ul>
    @endif

    @if($wrestler->hasCurrentManagers())
        <h2>Current Managers</h2>
        <ul>
            @foreach($wrestler->currentManagers as $manager)
                <li>{{ $manager->present()->fullName() }}</li>
            @endforeach
        </ul>
    @endif

    @if($wrestler->hasPreviousManagers())
        <h2>Previous Managers</h2>
        <ul>
            @foreach($wrestler->previousManagers as $manager)
                <li>{{ $manager->present()->fullName() }}</li>
            @endforeach
        </ul>
    @endif

    @if ($wrestler->hasPreviousTitlesHeld())
        <h2>Previous Titles Held</h2>
        <ul>
            @foreach($wrestler->previousTitlesHeld->groupByTitle() as $titles)
                <li>{{ "{$titles->first()->title->name} ({$titles->count()}x)" }}</li>
            @endforeach
        </ul>
    @endif

    @if($wrestler->hasPreviousInjuries())
        <h2>Previous Injuries</h2>
        <ul>
            @foreach($wrestler->previousInjuries as $injury)
                <li>{{ $injury->injured_at->format('F jS, Y') }} to {{ $injury->healed_at->format('F jS, Y') }}</li>
            @endforeach
        </ul>
    @endif

    @if($wrestler->hasPreviousRetirements())
        <h2>Previous Retirements</h2>
        <ul>
            @foreach($wrestler->previousRetirements as $retirement)
                <li>{{ $retirement->retired_at->format('F jS, Y') }} to {{ $retirement->ended_at->format('F jS, Y') }}</li>
            @endforeach
        </ul>
    @endif

    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-trophy"></i>List of Matches</h3>
            <div class="panel-actions">
                <a class="btn btn-default pull-right" href="{{ route('wrestlers.index') }}">Back to Wrestlers</a>
            </div>
        </div>
        <div class="panel-body container-fluid">
            <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                <thead>
                <th>ID</th>
                <th>Event</th>
                <th>Opponent(s)</th>
                <th>Actions</th>
                </thead>
                <tbody>
                @foreach($wrestler->matches as $match)
                    <tr>
                        <td>{{ $match->id }}</td>
                        <td>{{ $match->event->name }}</td>
                        {{--<td>{{ $match->competitors }}</td>--}}
                        <td> </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
