@extends('layouts.app')

@section('content')
    <h1>{{ $stipulation->name }}</h1>

    @if ($stipulation->matches->count() > 0)
        <div class="panel panel-bordered panel-primary">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left d-inline-block"><i class="icon fa-building"></i>Matches</h3>
            </div>
            <div class="panel-body container-fluid">
                <table class="table table-bordered">
                    <thead>
                        <th>Event</th>
                    </thead>
                    <tbody>
                    @foreach($stipulation->matches as $match)
                        <tr>
                            <td>{{ $match->event->name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
