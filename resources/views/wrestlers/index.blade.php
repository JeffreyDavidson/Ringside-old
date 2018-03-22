@extends('layouts.app')

@section('header')
    <h1 class="page-title">Wrestlers</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-group"></i>List of Wrestlers</h3>
            @can('create', App\Models\Wrestler::class)
                <div class="panel-actions">
                    <a class="btn btn-default pull-right" href="{{ route('wrestlers.create') }}">Create Wrestler</a>
                </div>
            @endcan
        </div>
        <div class="panel-body container-fluid">
            <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Height</th>
                    <th>Weight</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                    @foreach($wrestlers as $wrestler)
                        <tr>
                            <td>{{ $wrestler->id }}</td>
                            <td>{{ $wrestler->name }}</td>
                            <td>{{ $wrestler->status->name }}</td>
                            <td>{{ $wrestler->present()->height }}</td>
                            <td>{{ $wrestler->weight }}</td>
                            <td>
                                @include('partials.actions', ['resource' => 'wrestlers', 'model' => $wrestler])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
