@extends('layouts.app')

@section('header')
    <h1 class="page-title">Create Venue</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-building"></i>Create Venue Form</h3>
            <div class="panel-actions">
                <a class="btn btn-default pull-right" href="{{ route('venues.index') }}">Back to Venues</a>
            </div>
        </div>
        <div class="panel-body container-fluid">
            <form method="POST" action="{{ route('venues.index') }}">
                @include('venues.partials.form', compact('venue'))
            </form>
        </div>
    </div>
@endsection
