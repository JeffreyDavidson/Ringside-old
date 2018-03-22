@extends('layouts.app')

@section('header')
    <h1 class="page-title">Titles</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-trophy"></i>List of Titles</h3>
            @can('create', App\Models\Title::class)
                <div class="panel-actions">
                    <a class="btn btn-default pull-right" href="{{ route('titles.create') }}">Create Title</a>
                </div>
            @endcan
        </div>
        <div class="panel-body container-fluid">
            <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Introduced At</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                    @foreach($titles as $title)
                        <tr>
                            <td>{{ $title->id }}</td>
                            <td>{{ $title->name }}</td>
                            <td>{{ $title->slug }}</td>
                            <td>{{ $title->present()->introduced_at }}</td>
                            <td>
                                @include('partials.actions', ['resource' => 'titles', 'model' => $title])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
