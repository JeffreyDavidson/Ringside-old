@extends('layouts.app')

@section('header')
    <h1 class="page-title">Titles</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">

            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-trophy"></i>{{ $title->name }}</h3>
            <div class="panel-actions">
                <a class="btn btn-default pull-right" href="{{ route('titles.index') }}">Back To All Titles</a>
            </div>
        </div>
        <div class="panel-body container-fluid">
            <img src="{{ asset('/images/unknown.jpg') }}" alt="{{ $title->name }} Title Image">
            <p>Introduced At: {{ $title->formatted_introduced_at }}</p>
            @if ($title->retired_at)
                <p>Retired At: {{ $title->formatted_retired_at }}</p>
            @endif
            <h2>Records</h2>
            <p>Longest Title Reign: {{ $title->longest_title_reign }}</p>
            <p>Most Title Defenses: {{ $title->most_title_defenses }}</p>
            <p>Most Title Reigns: {{ $title->most_title_reigns }}</p>
            <h2>Champions</h2>
            <table id="matchesList" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <th>Champion</th>
                    <th>Won On</th>
                    <th>Lost On</th>
                </thead>
                <tbody>
                @foreach($title->champions as $champion)
                    <tr>
                        <td>{{ $champion->wrestler->name }}</td>
                        <td>{{ $champion->formatted_won_on }}</td>
                        <td>{{ $champion->formatted_lost_on }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <h2>Matches</h2>
            <table id="matchesList" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <th>Event</th>
                </thead>
                <tbody>
                @foreach($title->matches as $match)
                    <tr>
                        <td>{{ $match->event->name }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection