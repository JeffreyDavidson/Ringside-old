@extends('layouts.app')

@section('header')
    <h1 class="page-title">Wrestlers</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-group"></i>List of Wrestlers</h3>
            @can('create', App\Models\Wrestler::class)
                <div class="panel-actions">
                    <a class="btn btn-default pull-right" href="{{ route('wrestlers.create') }}">Create Wrestler</a>
                </div>
            @endcan
        </div>
        <div class="panel-body container-fluid">
            @include('wrestlers.partials.table')
            {{ $wrestlers->links() }}
        </div>
    </div>
@endsection
