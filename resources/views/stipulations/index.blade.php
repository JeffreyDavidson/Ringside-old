@extends('layouts.app')

@section('header')
    <h1 class="page-title">Stipulations</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-gavel"></i>List of Stipulations</h3>
        </div>
        <div class="panel-body container-fluid">
            @include('stipulations.partials.table')
            {{ $stipulations->links() }}
        </div>
    </div>
@endsection
