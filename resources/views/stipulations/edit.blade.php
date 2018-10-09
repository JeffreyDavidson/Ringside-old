@extends('layouts.app')

@section('header')
    <h1 class="page-title">Edit Stipulation</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-gavel"></i>Edit Stipulation Form</h3>
        </div>
        <div class="panel-body container-fluid">
            <form method="POST" action="{{ route('stipulations.update', $stipulation->id) }}">
                {{ method_field('PATCH') }}
                @include('stipulations.partials.form', ['submitButtonText' => 'Edit Stipulation'])
            </form>
        </div>
    </div>
@endsection
