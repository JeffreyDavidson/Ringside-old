@extends('layouts.app')

@section('header')
    <h1 class="page-title">Stipulations</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-gavel"></i>List of Stipulations</h3>
            @can('create', \App\Models\Stipulation::class)
                <div class="panel-actions">
                    <a class="btn btn-default pull-right" href="{{ route('stipulations.create') }}">Create Stipulation</a>
                </div>
            @endcan
        </div>
        <div class="panel-body container-fluid">
            @include('stipulations.partials.table')
            {{ $stipulations->links() }}
        </div>
    </div>
@endsection
