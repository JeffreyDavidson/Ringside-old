@extends('layouts.app')

@section('header')
    <h1 class="page-title">Show Stipulation</h1>
@endsection

@section('content')
    <div class="panel panel-primary panel-bordered">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-info-circle"></i>Basic Info</h3>
            <div class="panel-actions">
                <a class="btn btn-default pull-right" href="{{ route('stipulations.index') }}">Back To All Stipulations</a>
            </div>
        </div>
        <div class="panel-body">
            <p>Stipulation Name: {{ $stipulation->name }}</p>
        </div>
    </div>
    @if ($stipulation->hasPastMatches())
        <div class="panel panel-bordered panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="icon fa-calendar"></i>List of Previous Matches</h3>
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
                    @foreach($stipulation->pastMatches() as $match)
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
    @endif
@endsection
