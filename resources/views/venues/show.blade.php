@extends('layouts.app')

@section('header')
    <h1 class="page-title">Show Venue</h1>
@endsection

@section('content')
    <div class="panel panel-primary panel-bordered">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-info-circle"></i>Basic Info</h3>
        </div>
        <div class="panel-body">
            <p>Venue Name: {{ $venue->name }}</p>
            <p>Address:</p>
            <p>{{ $venue->address }}<br>{{ $venue->city }}, {{ $venue->state }} {{ $venue->postcode }}</p>
        </div>
    </div>
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="icon fa-calendar"></i>List of Past Events</h3>
        </div>
        <div class="panel-body container-fluid">
            @include('venues.partials.events')
        </div>
    </div>
@endsection
