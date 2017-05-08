@extends('layouts.app')

@section('header')
    <h1 class="page-title">Wrestlers</h1>
@endsection

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-group"></i>Wrestlers</h3>
            <div class="panel-actions">
                <a class="btn btn-default pull-right" href="{{ route('wrestlers.create') }}">Create Wrestler</a>
            </div>
        </div>
        <div class="panel-body container-fluid" is="wrestler-table" :wrestler_list="{{$wrestlers}}" inline-template>
            <div>
                <select class="form-control" id="status_id" name="status_id" v-model="status" v-if="status !== null">
                    <option value="0">Choose One</option>
                    <option :value="status.id" v-for="status in statuses">@{{ status.name }}</option>
                </select>
                <vuetable ref="vuetable" :api-mode="false" :data="wrestler_list" :fields="['id', 'name']">
                </vuetable>
                {{--<table id="wrestlersList" class="table table-striped table-bordered" cellspacing="0" width="100%">--}}
                    {{--<thead>--}}
                        {{--<th @click="sort('ID')">ID</th>--}}
                        {{--<th @click="sort('Name')">Name</th>--}}
                        {{--<th>Actions</th>--}}
                    {{--</thead>--}}
                    {{--<tbody>--}}
                        {{--<tr is="wrestler-row" v-for="wrestler in wrestlers" :wrestler="wrestler" url="{{route('wrestlers.index')}}"></tr>--}}
                    {{--@foreach($wrestlers as $wrestler)--}}
                        {{--<tr>--}}
                            {{--<td>{{ $wrestler->id }}</td>--}}
                            {{--<td>{{ $wrestler->name }}</td>--}}
                            {{--<td>--}}
                                {{--<a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route('wrestlers.edit', ['id' => $wrestler->id]) }}" data-toggle="tooltip" data-original-title="Edit">--}}
                                    {{--<i class="icon wb-wrench" aria-hidden="true"></i>--}}
                                {{--</a>--}}
                                {{--<a class="btn btn-sm btn-icon btn-flat btn-default" href="{{ route('wrestlers.show', ['id' => $wrestler->id]) }}" data-toggle="tooltip" data-original-title="Show">--}}
                                    {{--<i class="icon wb-eye" aria-hidden="true"></i>--}}
                                {{--</a>--}}
                                {{--<button class="btn btn-sm btn-icon btn-flat btn-default" type="button" data-toggle="tooltip" data-original-title="Delete">--}}
                                    {{--<i class="icon wb-close" aria-hidden="true"></i>--}}
                                {{--</button>--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                    {{--@endforeach--}}
                    {{--</tbody>--}}
                {{--</table>--}}
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    {{--<script type="text/javascript">--}}
        {{--$(document).ready(function() {--}}
            {{--$('#wrestlersList').DataTable({--}}
                {{--"pagingType": "full_numbers",--}}
                {{--"columnDefs": [--}}
                    {{--{ "width": "20px", "targets": 0 },--}}
                    {{--{ "width": "150px", "targets": -1 },--}}
                    {{--{ "targets": -1, "orderable": false }--}}
                {{--]--}}
            {{--});--}}
        {{--} );--}}
    {{--</script>--}}


@endsection