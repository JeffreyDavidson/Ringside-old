@extends('layouts.app')

@section('header')
    <h1 class="page-title">Create Event</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-calendar"></i>Create Event Form</h3>
        </div>
        <div class="panel-body container-fluid">
            <form method="POST" action="{{ route('events.store') }}">
                @include('events.partials.form', compact('event'))
            </form>
        </div>
    </div>
@endsection
