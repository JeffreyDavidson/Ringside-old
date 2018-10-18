@extends('layouts.app')

@section('header')
    <h1 class="page-title">Schedule Matches</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-gavel"></i>Schedule Match Form</h3>
        </div>
        <div class="panel-body container-fluid">
            <form method="POST" action="{{ route('matches.index', request()->event->id) }}">
                @include('matches.partials.form')
            </form>
        </div>
    </div>
@endsection