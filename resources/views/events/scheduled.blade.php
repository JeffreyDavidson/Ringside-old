@extends('layouts.app')

@section('header')
    <h1 class="page-title">Events</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-calendar"></i>List of Scheduled Events</h3>
            @can('create', App\Models\Event::class)
                <div class="panel-actions">
                    <a class="btn btn-default pull-right" href="{{ route('events.create') }}">Create Event</a>
                </div>
            @endcan
        </div>
        <div class="panel-body container-fluid">
            @include('events.partials.table', ['events' => $scheduledEvents, 'actions' => collect(['edit', 'show', 'delete'])])
            {{ $scheduledEvents->links() }}
        </div>
    </div>
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-calendar"></i>List of Previous Events</h3>
        </div>
        <div class="panel-body container-fluid">
            @include('events.partials.table', ['events' => $previousEvents, 'actions' => collect(['show', 'archive'])])
            {{ $previousEvents->links() }}
        </div>
    </div>
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-calendar"></i>List of Archived Events</h3>
        </div>
        <div class="panel-body container-fluid">
            @include('events.partials.table', ['events' => $archivedEvents, 'actions' => collect(['show'])])
            {{ $archivedEvents->links() }}
        </div>
    </div>
@endsection
