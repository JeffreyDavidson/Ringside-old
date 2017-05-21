@extends('layouts.app')

@section('header')
    <h1 class="page-title">Create Stipulation</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-gavel"></i>Create Stipulation Form</h3>
            <div class="panel-actions">
                <a class="btn btn-default pull-right" href="{{ route('stipulations.index') }}">Back to Stipulations</a>
            </div>
        </div>
        <div class="panel-body container-fluid">
            <form method="POST" action="{{ route('stipulations.index') }}">
                @include('stipulations.form', compact('stipulation'))
            </form>
        </div>
    </div>
@endsection
