@extends('layouts.app')

@section('header')
    <h1 class="page-title">Show Stipulation</h1>
@endsection

@section('content')
    <div class="panel panel-primary panel-bordered">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-info-circle"></i>Basic Info</h3>
        </div>
        <div class="panel-body">
            <p>Stipulation Name: {{ $stipulation->name }}</p>
        </div>
    </div>
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="icon fa-calendar"></i>List of Past Matches</h3>
        </div>
        <div class="panel-body container-fluid">
            @include('stipulations.partials.events')
        </div>
    </div>
@endsection
