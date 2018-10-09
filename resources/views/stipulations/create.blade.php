@extends('layouts.app')

@section('header')
    <h1 class="page-title">Create Stipulation</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-gavel"></i>Create Stipulation Form</h3>
        </div>
        <div class="panel-body container-fluid">
            <form method="POST" action="{{ route('stipulations.index') }}">
                @include('stipulations.partials.form', compact('stipulation'))
            </form>
        </div>
    </div>
@endsection
