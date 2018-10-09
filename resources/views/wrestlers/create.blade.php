@extends('layouts.app')

@section('header')
    <h1 class="page-title">Create Wrestler</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-group"></i>Create Wrestler Form</h3>
        </div>
        <div class="panel-body container-fluid">
            <form method="POST" action="{{ route('wrestlers.store') }}">
                @include('wrestlers.partials.form', compact('wrestler'))
            </form>
        </div>
    </div>
@endsection
