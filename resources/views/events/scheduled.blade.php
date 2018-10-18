@extends('layouts.app')

@section('header')
    <h1 class="page-title">Scheduled Events</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-calendar"></i>List of Scheduled Events</h3>
        </div>
        <div class="panel-body container-fluid">
            @include('events.partials.table', ['actions' => collect(['edit', 'view', 'delete', 'book-matches'])])
            {{ $events->links() }}
        </div>
    </div>
@endsection
