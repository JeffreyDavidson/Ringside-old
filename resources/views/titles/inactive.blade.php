@extends('layouts.app')

@section('header')
    <h1 class="page-title">Inactive Titles</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-calendar"></i>List of Inactive Titles</h3>
        </div>
        <div class="panel-body container-fluid">
            @include('titles.partials.table', ['actions' => collect(['edit', 'view', 'delete', 'activate', 'retire'])])
            {{ $titles->links() }}
        </div>
    </div>
@endsection
