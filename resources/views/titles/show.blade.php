@extends('layouts.app')

@section('header')
    <h1 class="page-title">Show Title</h1>
@endsection

@section('content')
    <div class="panel panel-primary panel-bordered">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-info-circle"></i>Basic Info</h3>
            <div class="panel-actions">
                <a class="btn btn-default pull-right" href="{{ url()->previous() }}">Previous Page</a>
            </div>
        </div>
        <div class="panel-body">
            <p>Title Name: {{ $title->name }}</p>
            <img src="{{ asset('/images/unknown.jpg') }}" alt="{{ $title->name }} Title Image">
            <p>Introduced At: {{ $title->present()->introduced_at }}</p>
            @if ($title->retired_at)
                <p>Retired At: {{ $title->present()->retired_at }}</p>
            @endif
            @include('titles.partials.records')
        </div>
    </div>
    @if ($title->hasPastMatches())
        <div class="panel panel-bordered panel-primary">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-trophy"></i>Previous Champions</h3>
            </div>
            <div class="panel-body container-fluid">
                @include('titles.partials.champions')
            </div>
        </div>
        <div class="panel panel-bordered panel-primary">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-calendar"></i>Matches</h3>
            </div>
            <div class="panel-body container-fluid">
                @include('titles.partials.matches')
            </div>
        </div>
    @endif
@endsection
