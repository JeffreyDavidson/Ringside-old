@extends('layouts.app')

@section('header')
    <h1 class="page-title">Edit Event</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-building"></i>Edit Event Form</h3>
        </div>
        <div class="panel-body container-fluid">
            <form method="POST" action="{{ route('events.update', $event->id) }}">
                {{ method_field('PATCH') }}
                @include('events.partials.form', ['submitButtonText' => 'Edit Event'])
            </form>
        </div>
    </div>
@endsection
