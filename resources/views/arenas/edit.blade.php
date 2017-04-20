@extends('layouts.app')

@section('header')
    <h1 class="page-title">Arenas</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-building"></i>Edit Arena Form</h3>
            <div class="panel-actions">
                <a class="btn btn-default pull-right" href="{{ route('arenas.index') }}">Back to Arenas</a>
            </div>
        </div>
        <div class="panel-body container-fluid">
            <div class="row row-lg">
                <div class="col-md-6">
                    <form method="POST" action="{{ route('arenas.update', $arena->id) }}">
                        {{ method_field('PATCH') }}
                        @include('arenas.form', ['submitButtonText' => 'Edit Arena'])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
