@extends('layouts.app')

@section('header')
    <h1 class="page-title">Titles</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-trophy"></i>List of Active Titles</h3>
            @can('create', App\Models\Title::class)
                <div class="panel-actions">
                    <a class="btn btn-default pull-right" href="{{ route('titles.create') }}">Create Title</a>
                </div>
            @endcan
        </div>
        <div class="panel-body container-fluid">
            @include('titles.partials.table', ['titles' => $activeTitles, 'actions' => collect(['edit', 'show', 'delete', 'retire'])])
            {{ $activeTitles->links() }}
        </div>
    </div>
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-calendar"></i>List of Retired Titles</h3>
        </div>
        <div class="panel-body container-fluid">
            @include('titles.partials.table', ['titles' => $retiredTitles, 'actions' => collect(['edit', 'show', 'delete', 'unretire'])])
            {{ $retiredTitles->links() }}
        </div>
    </div>
@endsection
