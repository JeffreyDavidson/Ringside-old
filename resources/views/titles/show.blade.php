@extends('layouts.app')

@section('header')
    <h1 class="page-title">Show Title</h1>
@endsection

@section('content')
    <div class="panel panel-primary panel-bordered">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-info-circle"></i>Basic Info</h3>
            <div class="panel-actions">
                <a class="btn btn-default pull-right" href="{{ route('titles.index') }}">Back To All Titles</a>
            </div>
        </div>
        <div class="panel-body">
            <p>Title Name: {{ $title->name }}</p>
            <img src="{{ asset('/images/unknown.jpg') }}" alt="{{ $title->name }} Title Image">
            <p>Introduced At: {{ $title->present()->introduced_at }}</p>
            @if ($title->retired_at)
                <p>Retired At: {{ $title->present()->retired_at }}</p>
            @endif
            @if ($title->matches->count() > 0)
                <p>Records</p>
                <p>Longest Title Reign:
                    @foreach ($title->longest_title_reign() as $reign)
                        {{ $reign->name }} {{  "(".$reign->length." days)" }}
                    @endforeach
                </p>
                <p>Most Title Defenses:
                    @foreach ($title->most_title_defenses() as $defense)
                        {{ $defense->name }} {{  "(".$defense->count.")" }}
                    @endforeach
                </p>
                <p>Most Title Reigns:
                    @foreach ($title->most_title_reigns() as $reign)
                        {{ $reign->name }} {{  "(".$reign->count.")" }}
                    @endforeach
                </p>
            @endif
        </div>
    </div>
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-trophy"></i>Champions</h3>
        </div>
        <div class="panel-body container-fluid">
            <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                <thead>
                    <th>ID</th>
                    <th>Champion</th>
                    <th>Won On</th>
                    <th>Lost On</th>
                    <th>Length of Reign</th>
                </thead>
                <tbody>
                @foreach($title->champions as $champion)
                    <tr>
                        <td>{{ $champion->id }}</td>
                        <td>{{ $champion->wrestler->name }}</td>
                        <td>{{ $champion->present()->won_on }}</td>
                        <td>{{ $champion->present()->lost_on }}</td>
                        <td>{{ $champion->present()->length_of_reign }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-calendar"></i>Matches</h3>
        </div>
        <div class="panel-body container-fluid">
            <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                <thead>
                    <th>ID</th>
                    <th>Event</th>
                    <th>Match</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                @foreach($title->matches as $match)
                    <tr>
                        <td>{{ $match->id }}</td>
                        <td>{{ $match->event->name }}</td>
                        <td>{{ $match->present()->wrestlers }}</td>
                        <td>
                            <a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route('events.show', $match->event->id) }}" data-toggle="tooltip" data-original-title="Show">
                                <i class="icon wb-eye" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
