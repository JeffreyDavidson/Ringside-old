@extends('layouts.app')

@section('header')
    <h1 class="page-title">{{ $wrestler->name }}</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
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
    </div>

    @if ($wrestler->isCurrentlyAChampion())
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

    <h2>Past Titles Held</h2>
    <ul>
        @if ($wrestler->titles->isNotEmpty())
            @foreach ($wrestler->pastTitlesHeld->groupByTitle() as $title)
                <li>{{ "{$titles->first()->title->name} ({$titles->count()}x)" }}</li>    
            @endforeach
        @else 
            <li>No titles held.</li>    
        @endif    
    </ul>

    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-trophy"></i>List of Past Matches</h3>
        </div>
        <div class="panel-body container-fluid">
            <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                <thead>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Match</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                    @forelse ($wrestler->matches()->paginate() as $match)
                        <tr>
                            <td>{{ $match->event->name }}</td>
                            <td>{{ $match->date->format('F d, Y') }}</td>
                            <td>{{ $match->present()->wrestlers }}</td>
                            <td>actions</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">No matches for this wrestler.</td></tr>    
                    @endforelse
                </tbody>
            </table>

            {{ $wrestler->matches()->paginate()->links() }}
        </div>
    </div>
@endsection
