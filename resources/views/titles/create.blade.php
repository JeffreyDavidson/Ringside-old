@extends('layouts.app')

@section('header')
    <h1 class="page-title">Create Title</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-trophy"></i>Create Title Form</h3>
        </div>
        <div class="panel-body container-fluid">
            <form method="POST" action="{{ route('titles.store') }}">
                @include('titles.partials.form', compact('title'))
            </form>
        </div>
    </div>
@endsection
