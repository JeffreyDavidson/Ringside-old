@extends('layouts.app')

@section('header')
    <h1 class="page-title">{{ $wrestler->name }}</h1>
@endsection

@section('content')
    <p>Hometown: {{ $wrestler->hometown }}</p>
    <p>Height: {!!  $wrestler->height !!}</p>
    <p>Weight: {{ $wrestler->weight }} lbs.</p>

    @if($wrestler->signature_move)
        <p>Signature Move: {{ $wrestler->signature_move }}</p>
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
        <ul>
        @foreach($wrestler->titles->groupByTitle() as $titles)
            <li>{{ "{$titles->first()->title->name} ({$titles->count()}x)" }}</li>
        @endforeach
        </ul>
    @endif

    @if($wrestler->injuries->count() > 0)
        <h2>Previous Injuries</h2>
        @foreach($wrestler->injuries as $injury)
            {{ $injury->injured_at->format('F m, Y') }} to {{ $injury->healed_at->format('F m, Y') }}
        @endforeach
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
