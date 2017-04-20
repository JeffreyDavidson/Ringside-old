@extends('layouts.app')

@section('header')
    <h1 class="page-title">Stipulations</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-gavel"></i>Edit Stipulation Form</h3>
            <div class="panel-actions">
                <a class="btn btn-default pull-right" href="{{ route('stipulations.index') }}">Back to Stipiulations</a>
            </div>
        </div>
        <div class="panel-body container-fluid">
            <div class="row row-lg">
                <div class="col-md-6">
                    <form method="POST" action="{{ route('stipulations.index') }}">
                        @include('stipulations.form', ['submitButtonText' => 'Edit Stipulation'])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
