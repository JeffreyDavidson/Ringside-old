@extends('layouts.app')

@section('header')
    <h1 class="page-title">{{ $wrestler->name }}</h1>
@endsection

@section('content')
    <!-- <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-trophy"></i>Wrestler Profile</h3>
        </div>
        <div class="panel-body container-fluid">
            <p>Hometown: {{ $wrestler->hometown }}</p>
            <p>Height: {{  $wrestler->present()->height }}</p>
            <p>Weight: {{ $wrestler->weight }} lbs.</p>

            @if ($wrestler->signature_move)
                <p>Signature Move: {{ $wrestler->signature_move }}</p>
            @endif
        </div>
    </div> -->

    <!-- @if ($wrestler->isCurrentlyAChampion())
        <h2>Current Titles</h2>
        <ul>
            @foreach ($wrestler->currentTitlesHeld() as $title)
                <li>{{ $title->name }}</li>
            @endforeach
        </ul>
    @endif

    @if ($wrestler->hasCurrentManagers())
        <h2>Current Managers</h2>
        <ul>
            @foreach ($wrestler->currentManagers() as $manager)
                <li>{{ $manager->present()->fullName() }}</li>
            @endforeach
        </ul>
    @endif

    @if ($wrestler->hasPastTitlesHeld())
        <h2>Previous Titles Held</h2>
        <ul>
            @foreach ($wrestler->pastTitlesHeld->groupByTitle() as $title)
                <li>{{ "{$titles->first()->title->name} ({$titles->count()}x)" }}</li>
            @endforeach
        </ul>
    @endif -->

    <!-- <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-trophy"></i>List of Matches</h3>
        </div>
        <div class="panel-body container-fluid">
            <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                <thead>
                    <th>ID</th>
                    <th>Event</th>
                    <th>Opponent(s)</th>
                </thead>
                <tbody>
                    @forelse ($wrestler->matches as $match)
                        <tr>
                            <td>{{ $match->id }}</td>
                            <td>{{ $match->event->name }}</td>
                            <td>{{ $match->competitors }}</td>
                        </tr>
                    @empty
                        <td colspan="3">No matches for this wrestler.</td>    
                    @endforelse
                </tbody>
            </table>
        </div>
    </div> -->
@endsection
