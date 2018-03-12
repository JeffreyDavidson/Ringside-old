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
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    @foreach($wrestlers as $wrestler)
                        <tr>
                            <td>{{ $wrestler->id }}</td>
                            <td>{{ $wrestler->name }}</td>
                            <td>
                                @can('edit', $wrestler)
                                    <a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route('wrestlers.edit', ['id' => $wrestler->id]) }}" data-toggle="tooltip" data-original-title="Edit">
                                        <i class="icon wb-wrench" aria-hidden="true"></i>
                                    </a>
                                @endcan
                                @can('show', $wrestler)
                                    <a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route('wrestlers.show', ['id' => $wrestler->id]) }}" data-toggle="tooltip" data-original-title="Show">
                                        <i class="icon wb-eye" aria-hidden="true"></i>
                                    </a>
                                @endcan
                                @can('delete', $wrestler)
                                    <form style="display: inline-block;" action="{{ route('wrestlers.destroy', $wrestler) }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button style="cursor: pointer" class="btn btn-sm btn-icon btn-flat btn-default" type="submit" data-toggle="tooltip" data-original-title="Delete">
                                            <i class="icon wb-close" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
        </div>
    </div>
@endsection
