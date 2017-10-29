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
                            @can('edit', $title)
                                <a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route('titles.edit', $title->id) }}" data-toggle="tooltip" data-original-title="Edit">
                                    <i class="icon wb-wrench" aria-hidden="true"></i>
                                </a>
                            @endcan
                            @can('show', $title)
                            <a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route('titles.show', $title->id) }}" data-toggle="tooltip" data-original-title="Show">
                                <i class="icon wb-eye" aria-hidden="true"></i>
                            </a>
                            @endcan
                            @can('delete', $title)
                                <form style="display: inline-block;" action="{{ route('titles.destroy', $title->id) }}" method="POST">
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
