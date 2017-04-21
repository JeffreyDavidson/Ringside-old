@extends('layouts.app')

@section('header')
    <h1 class="page-title">Titles</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-trophy"></i>Edit Title Form</h3>
            <div class="panel-actions">
                <a class="btn btn-default pull-right" href="{{ route('titles.index') }}">Back to Titles</a>
            </div>
        </div>
        <div class="panel-body container-fluid">
            <div class="row row-lg">
                <div class="col-md-6">
                    <form method="POST" action="{{ route('titles.update', $title->id) }}">
                        {{ method_field('PATCH') }}
                        @include('titles.form', ['submitButtonText' => 'Edit Title'])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
