@extends('layouts.app')

@section('header')
    <h1 class="page-title">Venues</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-building"></i>List of Venues</h3>
            @can('create', App\Models\Venue::class)
                <div class="panel-actions">
                    <a class="btn btn-default pull-right" href="{{ route('venues.create') }}">Create Venue</a>
                </div>
            @endcan
        </div>
        <div class="panel-body container-fluid">
            @include('venues.partials.table')
            {{ $venues->links() }}
        </div>
    </div>
@endsection
