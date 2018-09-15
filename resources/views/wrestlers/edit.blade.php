@extends('layouts.app')

@section('header')
    <h1 class="page-title">Edit Wrestler</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-group"></i>Edit Wrestler Form</h3>
            <div class="panel-actions">
                <a class="btn btn-default pull-right" href="{{ url()->previous() }}">Previous Page</a>
            </div>
        </div>
        <div class="panel-body container-fluid">
            <form method="POST" action="{{ route('wrestlers.update', $wrestler->id) }}">
                {{ method_field('PATCH') }}
                @include('wrestlers.partials.form', [
                    'submitButtonText' => 'Update Wrestler'
                ])
            </form>
        </div>
    </div>
@endsection
