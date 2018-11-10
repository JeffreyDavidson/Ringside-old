@extends('layouts.app')

@section('header')
    <h1 class="page-title">Archived Events</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-calendar"></i>List of Archived Events</h3>
        </div>
        <div class="panel-body container-fluid">
            @include('events.partials.table', ['events' => $archivedEvents, 'actions' => collect(['edit', 'view', 'delete', 'unarchive'])])
            {{ $archivedEvents->links() }}
        </div>
    </div>
@endsection
