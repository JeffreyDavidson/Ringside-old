@extends('layouts.app')

@section('header')
    <h1 class="page-title">Inactive Wrestlers</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-group"></i>List of Inactive Wrestlers</h3>
        </div>
        <div class="panel-body container-fluid">
            @include('wrestlers.partials.table', ['actions' => collect(['edit', 'view', 'delete', 'activate', 'retire'])])
            {{ $wrestlers->links() }}
        </div>
    </div>
@endsection